## Vendra Product API

The `misaf/vendra-product-api` package exposes `misaf/vendra-product` domain models through Laravel JSON:API.

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

### Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

- Keep API code inside `packages/vendra-product-api` using the `Misaf\VendraProductApi` namespace.
- Use this package for JSON:API servers, schemas, resources, query validators, API routes, service providers, and API tests.
- Import domain models from `Misaf\VendraProduct`; do not duplicate persistence or domain behavior in the API module.
- Keep Filament/admin UI in `misaf/vendra-product`.
- Respect domain model tenancy. Tenant awareness is owned by `misaf/vendra-support` and derives from the bound `TenantResolver` (installing `misaf/vendra-tenant` enables it); there is no `tenant_aware` config toggle.
- Keep production API code tenant-provider agnostic: inherit tenancy through the `Misaf\VendraProduct` models and never reference `Misaf\VendraTenant` in servers, schemas, queries, or routes. Feature tests may use a concrete tenant factory solely to establish tenant context; architecture expectations remain scoped to the production `Misaf\VendraProductApi` namespace.
- Keep schema filters and request validation rules synchronized.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic. Match the surrounding file and do not add comments that restate the code.
- Add or update Pest tests for routes, server schema registration, filters, includes, pagination, sorting, sparse fieldsets, and relationship endpoints.
- Keep tests purposeful and prevent unnecessary ones: cover behavior, contracts, and edge cases — not framework internals or trivially typed code. Do not duplicate coverage a focused test already proves, and do not add throwaway verification scripts when a test fits.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus a tenant-agnostic expectation, e.g. `arch()->expect('Misaf\VendraProductApi')->not->toUse('Misaf\VendraTenant')`.
