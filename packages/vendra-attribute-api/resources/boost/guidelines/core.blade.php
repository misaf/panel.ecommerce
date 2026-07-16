## Vendra Attribute API

The `misaf/vendra-attribute-api` package exposes attribute values from `misaf/vendra-attribute` through Laravel JSON:API.

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep API code inside `packages/vendra-attribute-api` using the `Misaf\VendraAttributeApi` namespace; keep models and persistence in `misaf/vendra-attribute`.
- Register only `AttributeValueSchema` on the v1 server unless a new resource contract is explicitly designed and tested.
- Preserve the current attribute-value shape: `attribute_id`, `value`, and read-only `position`; do not expose polymorphic type/ID internals by default.
- Keep endpoints read-only while `Server::authorizable()` is false; do not add mutations without an authorization design and tests.
- Keep schemas, resources, query validators (`AttributeValueQuery` / `AttributeValueCollectionQuery`), server registration, routes, filters, and tests synchronized; request validation rules must match the schema's filters and pagination.
- Inherit tenant isolation from `AttributeValue` and keep production API code free of `Misaf\VendraTenant` and API tenant toggles. Feature tests may use a concrete tenant factory solely to establish tenant context.
- Keep `tests/ArchTest.php` enforcing the PHP, security, and Laravel presets plus `not->toUse('Misaf\VendraTenant')`.
