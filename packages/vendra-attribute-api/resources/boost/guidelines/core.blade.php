## Vendra Attribute API

The `misaf/vendra-attribute-api` package exposes active attributes and their values from `misaf/vendra-attribute` through API Platform for Laravel.

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

### Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

- Keep API code inside `packages/vendra-attribute-api` using the `Misaf\VendraAttributeApi` namespace; keep models and persistence in `misaf/vendra-attribute`.
- Expose both the `AttributeResource` (`Attribute`) and `AttributeValueResource` (`AttributeValue`) `ApiResource` DTOs. Keep the operations read-only and synchronize the DTOs, state providers, query parameters, and tests when either contract changes.
- Preserve the attribute shape (`name`, `description`, `unit`, `position`, `active`) and the attribute-value shape (`attribute_id`, `value`, read-only `position`). List/show only active attributes and values belonging to active attributes; do not expose polymorphic type/ID internals.
- Bind `AttributeApiResolver` to this package's provider-neutral resolver so product APIs can obtain the attribute-value resource without importing this package. Product categories expose `attributeValues`; products expose `attributeValues` and `selectedAttributeValues`.
- Keep endpoints read-only and non-authorizable; do not add mutations without an authorization design and tests.
- Keep both resource families' DTOs, state providers, query parameters, service-provider registration, filters, and tests synchronized.
- Inherit tenant isolation from `AttributeValue` and keep production API code free of `Misaf\VendraTenant` and API tenant toggles. Feature tests may use a concrete tenant factory solely to establish tenant context.
- Keep `tests/ArchTest.php` enforcing the PHP, security, and Laravel presets plus `not->toUse('Misaf\VendraTenant')`.
