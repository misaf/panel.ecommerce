## Vendra Container

The `misaf/vendra-container` package is the platform's **container runtime infrastructure**. It manages containers, images, and networks through one typed contract, and knows nothing about the business domain that asks for them.

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

### Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

- Keep container-runtime code inside `packages/vendra-container` using the `Misaf\VendraContainer` namespace.
- **This package must not know what a Property, Reseller, Storefront, or tenant is.** It has no Vendra dependencies at all, and must not gain one. If a change here needs a business concept, the concept belongs in the caller and the change belongs in its `ContainerDefinition`.
- `Contracts\ContainerRuntime` is the only surface callers use. Both sides of every method are value objects (`ContainerDefinition`, `ContainerInfo`, `ContainerId`, `ImageReference`, `ImageInfo`, `NetworkDefinition`, `NetworkInfo`, `RuntimeStatus`, `ContainerLogs`). Do not add `array $payload` parameters or array returns — the stringly typed shape they replace pushed validation onto every caller.
- `Runtimes\DockerCompatibleRuntime` holds the entire Engine API implementation; `DockerRuntime` and `PodmanRuntime` subclass it. Podman serves the same API through its compatibility socket, so **do not duplicate the client for it** — a subclass exists only for what genuinely differs (its default API version, and dropping log options for a driver it does not use).
- `Http\EngineApiClient` is the only class allowed to mention sockets, URLs, or HTTP status codes. Runtimes translate its responses into value objects; nothing above the runtime sees a `Response`.
- Runtime selection is a binding, made once in `ContainerServiceProvider` from `config/vendra-container.php`. Callers type-hint `ContainerRuntime` and must never branch on which runtime answered.
- Read every setting through `Support\ContainerRuntimeConfiguration`, injected as an immutable value object. Do not add `Config::get('vendra-container.*')` calls elsewhere. It is bound with `bind()`, not `singleton()`, so a config change is picked up on the next resolve — keep it that way.
- `isConfigured()` answers "is any runtime configured" without touching the network, so a caller can record intent and reconcile later instead of blocking on a daemon that is not up.
- Normalize every failure to `Exceptions\ContainerRuntimeException` (or `ContainerNotFoundException` / `RuntimeUnreachableException`). Callers must never parse a runtime's error body.
- Idempotence is part of the contract: `remove()` treats an absent container as success, `start()` treats a running one as success, `stop()` treats a stopped one as success, and `find()` returns null where `inspect()` throws.
- `Support\ContainerHealthGate` owns the "wait until it serves" loop. A container reporting no health state is treated as ready once running — an image may carry no health check, and Podman without systemd never executes the one it was given — and the degradation is logged when a check was expected.
- `Testing\FakeContainerRuntime` is the supported way for callers to test against this package. Domain tests must never need a real daemon; extend the fake when the contract grows.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets, plus the expectation that this package uses no other `Misaf\Vendra*` namespace.
