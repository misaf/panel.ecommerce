---
name: vendra-container-development
description: "Create, modify, review, or test the Vendra Container module in packages/vendra-container, changing container runtime management for Docker and Podman. Use for ContainerRuntime, DockerCompatibleRuntime, DockerRuntime, PodmanRuntime, EngineApiClient, ContainerDefinition, ContainerInfo, ContainerId, ImageReference, ImageInfo, NetworkDefinition, NetworkInfo, RuntimeStatus, ContainerLogs, PortBinding, VolumeMount, EnvironmentVariable, HealthCheck, RestartPolicy, LogConfiguration, ContainerRuntimeConfiguration, ContainerHealthGate, FakeContainerRuntime, container lifecycle operations, image pulls, network lookups, and runtime error normalization."
---

# Vendra Container

## Workflow

- Inspect `composer.json`, sibling files, and existing tests before changing the package.
- Use Laravel Boost `application-info` and `search-docs` before code changes.
- Apply `laravel-best-practices` to Laravel PHP and `pest-testing` whenever tests change.
- Keep changes inside this package's boundary and preserve its public contracts.
- Add or update focused Pest coverage, then run `php artisan test --compact --testsuite=vendra-container` from the project root.

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

## Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

## Module Boundary

- This is the lowest layer of the platform. It has **no Vendra dependencies** and must never gain one: no Property, Reseller, Storefront, tenant, or Vendra-specific concept may appear in this package.
- The dependency arrow points one way — `vendra-console` → `vendra-reseller` → `vendra-property` → `vendra-container`. A change here that needs to know its caller is a change in the wrong package.
- Anything Docker-shaped stops at `Runtimes\DockerCompatibleRuntime::toEnginePayload()`: nanosecond intervals, `KEY=VALUE` environment strings, `ExposedPorts` keys, the empty object that means "attach with defaults". Callers describe intent; this package encodes it.

## Contract

- `Contracts\ContainerRuntime`: `ping`, `pull`, `inspectImage`, `create`, `start`, `stop`, `restart`, `remove`, `inspect`, `find`, `logs`, `findNetwork`, `createNetwork`.
- Every parameter and return is a value object. Never widen a method to `array $payload` or an array return.
- Idempotence is contractual: `remove()` succeeds on an absent container, `start()` on a running one, `stop()` on a stopped one. `find()` returns null where `inspect()` throws `ContainerNotFoundException`.
- Normalize failures to `Exceptions\ContainerRuntimeException` and its two subclasses. A caller must never see a runtime's raw error body.

## Runtimes

- Add capability to `DockerCompatibleRuntime`; add only genuine differences to a subclass. Podman speaks the same Engine API over its compatibility socket, so duplicating the client for it is the mistake this layout exists to prevent.
- Known differences today: the compatibility socket's default API version, and Podman rejecting log options for a driver it is not using.
- Selection is configuration (`config/vendra-container.php`) resolved into a binding in `ContainerServiceProvider`. Never add a runtime check in a caller.
- Read configuration only through `Support\ContainerRuntimeConfiguration`; keep it bound with `bind()` so config changes are picked up on the next resolve.

## Testing

- Use `Testing\FakeContainerRuntime` for anything above the runtime. Ordinary domain and application tests must not require a Docker or Podman daemon.
- Test the adapters with `Http::fake()` against Engine API URLs, asserting the request payload — that is where the Docker-shaped mapping is proven.
- When the contract grows, extend the fake in the same change, or every consumer's suite breaks on a method that does not exist.

## Filament

- This package ships no Filament surface. If one is ever added, resources with a cluster live in `src/Filament/Clusters/Resources/` and resources without one live in `src/Filament/Resources/`.
