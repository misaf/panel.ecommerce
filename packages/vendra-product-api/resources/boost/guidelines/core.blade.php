## Vendra Product API

The `misaf/vendra-product-api` package exposes `misaf/vendra-product` domain models through Laravel JSON:API.

### Standards

- Keep API code inside `packages/vendra-product-api` using the `Misaf\VendraProductApi` namespace.
- Use this package for JSON:API servers, schemas, resources, query validators, API routes, service providers, and API tests.
- Import domain models from `Misaf\VendraProduct`; do not duplicate persistence or domain behavior in the API module.
- Keep Filament/admin UI in `misaf/vendra-product`.
- Respect domain model tenancy. Tenant awareness is owned by `misaf/vendra-support` and derives from the bound `TenantResolver` (installing `misaf/vendra-tenant` enables it); there is no `tenant_aware` config toggle.
- Keep the module tenant-agnostic: it must build and run with or without a tenant provider. Inherit tenancy through the `Misaf\VendraProduct` domain models and never reference a concrete provider such as `Misaf\VendraTenant` in servers, schemas, queries, routes, or tests.
- Keep schema filters and request validation rules synchronized.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic. Match the surrounding file and do not add comments that restate the code.
- Add or update Pest tests for routes, server schema registration, filters, includes, pagination, sorting, sparse fieldsets, and relationship endpoints.
- Keep tests purposeful and prevent unnecessary ones: cover behavior, contracts, and edge cases — not framework internals or trivially typed code. Do not duplicate coverage a focused test already proves, and do not add throwaway verification scripts when a test fits.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus a tenant-agnostic expectation, e.g. `arch()->expect('Misaf\VendraProductApi')->not->toUse('Misaf\VendraTenant')`.
