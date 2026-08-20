# Vendra Estate

Everything needed to run Vendra in production: the Traefik edge, the platform
services, and the optional marketing site. The platform is the source of truth
for them; no external controller is involved.

```
docker/stacks/
  bin/vendra              estate control (host-level)
  .env.example            one file configures everything
  proxy/                  Traefik, its static config, and file-provider dynamic config
  platform/               mysql, redis, php, php-api, horizon, storefront-worker, scheduler, pulse
  website/                optional marketing site
```

## Quick start

```sh
cp docker/stacks/.env.example docker/stacks/.env
$EDITOR docker/stacks/.env          # BASE_DOMAIN, images, passwords, APP_KEY
docker/stacks/bin/vendra up
```

`up` creates the network, prepares the state directory, renders
`platform/platform.env`, then starts proxy → platform → website, waiting for each
to become healthy.

| Command | |
| --- | --- |
| `vendra up` | render config, ensure the network, start every stack |
| `vendra down` | stop every stack |
| `vendra restart` | down then up |
| `vendra ps` | stack services plus the storefront fleet |
| `vendra logs [target]` | follow logs — `proxy`, `website`, a platform service, or a container |
| `vendra artisan <args>` | run artisan inside the platform container |
| `vendra urls` | print the estate's URLs |
| `vendra hosts` | print an `/etc/hosts` block for a local estate |
| `vendra certs` | issue a local certificate with mkcert |

## Why the CLI is a shell script

The platform runs *inside* the stack it would otherwise be asked to start, so it
cannot bootstrap itself — something on the host has to run the first
`compose up`. Everything after bring-up is an artisan command inside the
container and is reachable through `vendra artisan`.

## Docker or Podman

Both serve the Engine API these stacks and the platform speak, so either runs the
estate. Name the runtime and its socket in `.env`:

```sh
CONTAINER_RUNTIME=podman
CONTAINER_SOCKET=/run/user/1000/podman/podman.sock   # rootless
STOREFRONT_LOG_DRIVER=k8s-file
```

`bin/vendra` drives `$CONTAINER_RUNTIME compose|network|ps|inspect|logs`, and the
socket is mounted into `storefront-worker` at the Docker path either way, so the
`CONTAINER_ENDPOINT` it writes into `platform.env` never changes. What does change
is `CONTAINER_RUNTIME`, which is passed through to the platform so it selects the
matching adapter and the Engine API version that runtime accepts.

Rootless Podman is the safer choice, and it is the one thing that changes the
security story of this estate: the socket that container holds stops being
root-equivalent on the host. Four things to know before switching:

- Enable the socket: `systemctl --user enable --now podman.socket`, plus
  `loginctl enable-linger <user>` so it survives logout.
- **Logging.** `json-file` and its `max-size`/`max-file` options are Docker's.
  Podman logs through `k8s-file` or journald and rejects options for a driver it
  is not using. Set `STOREFRONT_LOG_DRIVER` accordingly, or empty to inherit.
- **Health checks.** Podman runs a container's `HEALTHCHECK` through transient
  systemd timers. Without systemd the health state never populates, the
  provisioner's gate degrades to "started rather than healthy", and it logs a
  warning each time it does. Deployments still land; you just stop learning that
  a storefront booted broken.
- **Restart on boot.** `unless-stopped` is honored while Podman is running, but
  surviving a reboot needs `podman-restart.service` enabled. Docker's daemon does
  this for free.

## Storefronts are not here

The platform creates, replaces, and health-gates storefront containers itself
through the Engine API — see `App\Services\DockerStorefrontProvisioner`.
There is no compose project per store. A storefront is one container carrying
the `traefik.*` labels Traefik discovers and `io.vendra.*` labels marking it as
platform-owned, so the platform never touches a container it did not place.

`ProvisionStorefrontJob` runs on its own `storefronts` queue, served by the
`storefront-worker` container. That is the only container in the estate holding a
runtime socket, which is root-equivalent on the host under Docker (though not
under rootless Podman — see above). Horizon's supervisor
deliberately does not list that queue. **Do not** widen the worker's queues or
hand the socket to Horizon — that gives every queued job in the system host root.

Useful commands, all through `vendra artisan`:

```sh
vendra artisan storefront:status          # the fleet as the database sees it
vendra artisan storefront:reconcile       # correct whatever has drifted
vendra artisan storefront:retry-failed    # re-provision only the failures
vendra artisan storefront:redeploy        # rebuild everything (an outage)
```

`reconcile` compares each storefront against what the database intends and
applies the narrowest correction — starting a stopped container, stopping one
that should be down, rebuilding only what is missing, unhealthy, or serving the
wrong image. A converged estate comes through it untouched, so it is safe to run
often. Add `--sync` to run it in the foreground and see a per-outcome tally.

`redeploy` is the blunt instrument: it removes and recreates every storefront
meant to be running, each one down for its own pull and health check, one after
another. Reach for it when the change is one convergence cannot see — an image
republished under the same reference, say — and expect an outage.

## Certificates

Traefik owns TLS. Nothing in this repository generates a CA.

**Production** — set `CERT_RESOLVER=letsencrypt` and `ACME_EMAIL`. Traefik obtains
and renews every certificate over the HTTP challenge, storefront domains
included. Ports 80 and 443 must be reachable from the internet, and a customer
domain must resolve here before its certificate can be issued.

One exception: the per-tenant panels at `<slug>.admin.<base>` are matched by a
pattern, not a host, so the HTTP challenge has no single domain to prove. Serving
them over ACME needs a wildcard certificate for `*.admin.<base>`, which requires
the DNS challenge and a provider credential — add a `dnsChallenge` resolver and
`tls.domains` to the `tenant-panels` router in `proxy/dynamic/vendra.yml`. Without
that they fall back to the default certificate.

**Local** — leave `CERT_RESOLVER` empty and run `vendra certs`, which issues a
certificate with mkcert into `<state>/certificates`. Traefik serves it as the
default certificate. Re-run it after adding a store so the new domain is
covered, then `vendra restart`.

Storefronts also make server-side calls to the API. Under mkcert, Node rejects
that certificate and every call fails before it is sent — the page still renders,
so it shows only as empty sections. Set `STOREFRONT_CA_FILE` to have the CA
mounted read-only into each storefront and trusted.

HSTS is off unless `CERT_RESOLVER` is set. Sending it with a certificate the
browser does not trust pins the host to HTTPS *and* removes the "proceed anyway"
bypass, leaving a local `.test` host unreachable until HSTS state is cleared by
hand.

## Local estates

`.test` does not resolve publicly:

```sh
vendra hosts | sudo tee -a /etc/hosts
```

Traefik carries network aliases for `BASE_DOMAIN` and `api.BASE_DOMAIN` on
`traefik-public`. Without them, containers would follow the operator's
`/etc/hosts` entries to `127.0.0.1` — which inside a container is the container
itself, so a storefront's server-side call to the API fails with `ECONNREFUSED`
before it leaves the process.

## Exposure

Only ports 80 and 443 are public. MySQL and the Traefik dashboard bind to
loopback, reachable from the host or over `ssh -N -L …`. The dashboard is
unauthenticated — do not widen its address, and do not add a public router for
`api@internal`.
