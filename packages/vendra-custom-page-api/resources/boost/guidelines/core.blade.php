## Vendra Custom Page API

The `misaf/vendra-custom-page-api` package exposes `misaf/vendra-custom-page` domain models through Laravel JSON:API.

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

### Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

- Keep API code inside `packages/vendra-custom-page-api` using the `Misaf\VendraCustomPageApi` namespace.
- Import `CustomPage` and `CustomPageCategory` from `Misaf\VendraCustomPage`; do not duplicate persistence or domain behavior.
- Keep the API read-only and tenant-provider agnostic. Tenant scoping comes from the domain models through Vendra Support.
- Keep the API localization-package agnostic. Use Laravel's `api` middleware only; locale-aware filters read Laravel's current locale without requiring a resolver package.
- Expose translated `name`, `description`, and `slug` fields as JSON hashes using `getTranslations()` and keep locale-aware filters aligned with request validation.
- Validate query-string booleans with `JsonApiRule::boolean()->asString()` so validation matches boolean filter deserialization.
- Register `with-trashed` and `only-trashed` in both collection validation and schema filters.
- Map the domain models' `MorphMany` multimedia relations with JSON:API `HasMany` fields.
- Keep category, page, and multimedia relationships read-only, with explicit include paths and relationship routes.
- Add Pest coverage for routes, server schemas, filters, includes, pagination, sorting, relationship endpoints, and tenant isolation.
- Keep architecture tests preventing production code from referencing `Misaf\VendraTenant`.
