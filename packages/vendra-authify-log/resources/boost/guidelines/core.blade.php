## Vendra Authify Log

The `misaf/vendra-authify-log` package owns authentication activity logging (buffered via Redis) and the Filament admin UI for authentication log records.

### Standards

- Keep authify-log domain code inside `packages/vendra-authify-log` using the `Misaf\VendraAuthifyLog` namespace.
- Use this package for models, migrations, factories, seeders, policies, permission enums, observers, Filament resources, translations, config, and package bootstrapping.
- Log entries are buffered to Redis and drained by the channel command; the write path sets `tenant_id` explicitly via `TenantAwareness::currentId()` because it does not go through Eloquent's `BelongsToTenant` hook.
- Follow the concrete models and neighboring files in this package; do not apply translation, media, slug, sorting, or soft-delete patterns unless the affected model already uses them.
- Tenant awareness is owned by `misaf/vendra-support` via `Misaf\VendraSupport\Support\TenantAwareness`, which derives purely from the bound `TenantResolver`. Installing a tenant provider (e.g. `misaf/vendra-tenant`) makes the app tenant-aware; without one the default null resolver keeps it disabled. The module defines no `tenant_aware` config.
- Keep the module tenant-agnostic: it must build and run with or without a tenant provider. Never reference a concrete provider such as `Misaf\VendraTenant` anywhere — models, migrations, factories, seeders, or fixtures. Let `BelongsToTenant` assign `tenant_id`; do not set it manually.
- Keep the read-only Filament resource under `src/Filament/Clusters/Resources`, delegate table configuration to `Tables/AuthifyLogTable.php`, and preserve its shared `SystemCluster` assignment. Do not add a form schema unless authentication logs intentionally become editable.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic. Match the surrounding file and do not add comments that restate the code.
- Add or update Pest tests for policy coverage, config/navigation behavior, translation parity, model contracts, and user-visible Filament behavior.
- Keep tests purposeful and prevent unnecessary ones: cover behavior, contracts, and edge cases — not framework internals or trivially typed code. Do not duplicate coverage a focused test already proves, and do not add throwaway verification scripts when a test fits.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus a tenant-agnostic expectation, e.g. `arch()->expect('Misaf\VendraAuthifyLog')->not->toUse('Misaf\VendraTenant')`.
