# Vendra Docker

- `Dockerfile`, `Caddyfile`, `docker-entrypoint.sh`, `99-custom.ini` — the Laravel
  application image and its runtime.
- `stacks/` — the full production estate: Traefik, the platform services, and the
  optional marketing site. See [stacks/README.md](stacks/README.md).

The published image contains FrankenPHP, production Composer dependencies, and
compiled frontend assets. On startup the entrypoint waits for its configured
database and Redis services, runs migrations, and warms application caches. It
speaks plain HTTP on `:8080` and never terminates TLS — Traefik owns ports 80 and
443 and every certificate.

## Docker access

One container in the estate mounts `/var/run/docker.sock`: `storefront-worker`,
which runs the `storefronts` queue and nothing else. That is where storefront
containers are created, through the Docker Engine API rather than Compose.

A socket is root-equivalent on the host, so this isolation is the point. Horizon
runs every other queued job and has no socket, and its supervisor deliberately
does not list the `storefronts` queue. Do not widen the worker's queues or give
Horizon the socket.
