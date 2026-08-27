# Vendra Container

Runtime-agnostic container management for Laravel. One typed contract,
`ContainerRuntime`, implemented over the Docker Engine API — which Docker serves
directly and Podman serves through its compatibility socket.

This is the lowest layer of the Vendra platform. It has no Vendra dependencies
and knows nothing about stores, resellers, storefronts, or tenants: callers
describe *what* should run, and this package decides how the runtime is told to
run it.

## Requirements

- PHP 8.4+
- Laravel 13
- A reachable Docker or Podman endpoint (unix socket, `tcp://`, or `http(s)://`)

## Installation

```bash
composer require misaf/vendra-container
php artisan vendor:publish --tag=vendra-container-config
```

## Configuration

```dotenv
CONTAINER_RUNTIME=docker            # docker | podman
CONTAINER_ENDPOINT=unix:///var/run/docker.sock
CONTAINER_API_VERSION=              # empty negotiates the adapter's default
CONTAINER_TIMEOUT=60
CONTAINER_PULL_TIMEOUT=600
```

Rootless Podman is the safer endpoint to expose where the choice is open: its
socket is not root-equivalent on the host.

```dotenv
CONTAINER_RUNTIME=podman
CONTAINER_ENDPOINT=unix:///run/user/1000/podman/podman.sock
```

Selecting a runtime is the whole switch. Callers type-hint `ContainerRuntime` and
never learn which implementation answered.

### Confirming which daemon answers

```bash
php artisan container:status --network=traefik-public
```

`CONTAINER_RUNTIME` states an intent; the daemon states a fact. They can
disagree, because one socket path can be moved between daemons without the
configuration following it — `/var/run/docker.sock` in particular is claimed by
whichever of Docker Desktop and a Podman machine installed last. Both adapters
speak the same API, so a mismatch does not fail: it succeeds against the wrong
daemon, and the first symptom is a network, image, or container that has
apparently gone missing while sitting in plain sight on the other runtime.

`container:status` prints the configured runtime beside the engine's own `Server`
header and exits non-zero when they disagree. Prefer an unambiguous endpoint over
`/var/run/docker.sock`:

```bash
podman machine inspect --format '{{.ConnectionInfo.PodmanSocket.Path}}'
```

Networks, images, and containers are per-daemon and are **not** migrated by
changing this setting. Anything already deployed stays on the previous daemon,
still running and no longer managed; `storefront:status --runtime` in
`vendra-store` reports storefronts the current daemon has nothing for.

## Usage

```php
use Misaf\VendraContainer\Contracts\ContainerRuntime;
use Misaf\VendraContainer\ValueObjects\ContainerDefinition;
use Misaf\VendraContainer\ValueObjects\EnvironmentVariable;
use Misaf\VendraContainer\ValueObjects\ImageReference;
use Misaf\VendraContainer\ValueObjects\PortBinding;
use Misaf\VendraContainer\ValueObjects\ResourceLimits;
use Misaf\VendraContainer\ValueObjects\RestartPolicy;

$definition = new ContainerDefinition(
    name: 'store-101-storefront',
    image: new ImageReference('ghcr.io/misaf/vendra-storefront-florist:1.0.0'),
    environment: EnvironmentVariable::collection([
        'STORE_ID' => '101',
        'DOMAIN'      => 'flowers-a.com',
    ]),
    labels: ['traefik.enable' => 'true'],
    ports: [new PortBinding(3000)],
    networks: ['traefik-public'],
    restartPolicy: RestartPolicy::unlessStopped(),
    resources: new ResourceLimits(cpus: 0.5, memoryMegabytes: 512),
);

$runtime = app(ContainerRuntime::class);

$runtime->pull($definition->image);
$info = $runtime->create($definition);
$runtime->start($info->id);
```

### Contract

| Method | Purpose |
| --- | --- |
| `ping()` | Reports reachability and which runtime answered — never throws. |
| `pull(ImageReference)` | Fetches an image, failing on a mid-stream registry error. |
| `inspectImage(ImageReference)` | The local image, or `null`. |
| `create(ContainerDefinition)` | Creates a container without starting it. |
| `start` / `stop` / `restart` / `remove` | Lifecycle, by `ContainerId`. |
| `inspect(ContainerId)` | Throws `ContainerNotFoundException` when absent. |
| `find(ContainerId)` | Returns `null` when absent. |
| `logs(ContainerId)` | Captured output, stream headers stripped. |
| `findNetwork` / `createNetwork` | Generic network operations. |

### Resource limits

`ResourceLimits` caps one container in the units an operator thinks in — cores
and megabytes — and the adapter converts them to the Engine's nano-CPUs and
bytes. Every limit is optional, and an unset one means uncapped, so capping
memory while leaving CPU to the scheduler is a configuration rather than a
half-filled value. A definition with no configured limit sends no resource keys
at all: the Engine spells "unlimited" as a zero, and sending one by default
would cap by accident.

Idempotence is part of the contract: removing an absent container, starting a
running one, and stopping a stopped one all succeed. Every failure arrives as a
`ContainerRuntimeException`, so no caller parses a runtime's error body.

### Waiting for a container to serve

```php
use Misaf\VendraContainer\Support\ContainerHealthGate;

$ready = app(ContainerHealthGate::class)->awaitDefinition($runtime, $definition, timeoutSeconds: 120);
```

A container reporting no health state is treated as ready once it runs — an image
may carry no health check, and Podman without systemd never executes the one it
was given. When a check *was* requested, that degradation is logged.

## Testing

`FakeContainerRuntime` is the supported way to test the layers above this one. No
domain test should need a real daemon.

```php
use Misaf\VendraContainer\Contracts\ContainerRuntime;
use Misaf\VendraContainer\Testing\FakeContainerRuntime;

$runtime = (new FakeContainerRuntime())->withNetwork('traefik-public');

$this->app->instance(ContainerRuntime::class, $runtime);

// … exercise your action, then assert what it asked for:
expect($runtime->definitionFor('store-101-storefront')?->environment)->toHaveCount(2);
```

Run the package suite from the project root:

```bash
php artisan test --compact --testsuite=vendra-container
```

## License

MIT. See [LICENSE](LICENSE).
