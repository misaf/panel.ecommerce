## Vendra User Profile

The `misaf/vendra-user-profile` package owns per-user profiles attached to the user module and the Filament admin UI for user profiles.

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep user-profile domain code inside `packages/vendra-user-profile` using the `Misaf\VendraUserProfile` namespace.
- Use this package for models, migrations, factories, seeders, policies, permission enums, observers, Filament resources, translations, config, and package bootstrapping.
- Registers `profiles` / `userProfiles` on the `User` model via `User::resolveRelationUsing(...)` in the service provider; do not hard-code the relation on the `User` class.
- Keep optional profile domains in separate providers. `vendra-address`, `vendra-phone`, `vendra-document`, and `vendra-verification` depend one-way on this package and register dynamic relations plus Filament relation managers through `Support\UserProfileRelationManagers`; this package must never import those providers.
- Keep Pennant configuration flat with `features_enabled`, `features_discover`, and `module_enabled`; do not restore nested `features.*` keys. The installed module defaults to enabled so its resource appears with Users, while explicit environment overrides may disable it. Gate access through `Features\ModuleEnabled`; its `before()` hook rejects invalid tenant scopes and applies the global switches before stored values are considered.
- Follow the concrete models and neighboring files in this package; do not apply translation, media, slug, sorting, or soft-delete patterns unless the affected model already uses them.
- Tenant awareness is owned by `misaf/vendra-support` via `Misaf\VendraSupport\Support\TenantAwareness`, which derives purely from the bound `TenantResolver`. Installing a tenant provider (e.g. `misaf/vendra-tenant`) makes the app tenant-aware; without one the default null resolver keeps it disabled. The module defines no `tenant_aware` config.
- Keep the module tenant-agnostic: it must build and run with or without a tenant provider. Never reference a concrete provider such as `Misaf\VendraTenant` anywhere — models, migrations, factories, seeders, or fixtures. Let `BelongsToTenant` assign `tenant_id`; do not set it manually.
- `TenantSchema::addTenantColumn()` is evaluated when the migration runs. Install a tenant provider before migrating when profiles must be tenant-scoped; enabling tenancy later does not add `tenant_id` to an existing table.
- Keep the cluster-assigned Filament resource under `src/Filament/Clusters/Resources`, delegating forms to `Schemas/*Form.php` and tables to `Tables/*Table.php`.
- Keep `UserProfileResource::getRelations()` provider-neutral by resolving `UserProfileRelationManagers`; never hardcode an optional provider relation manager.
- Keep the complete resource tree under `src/Filament/Clusters/Resources/`, use the matching `Misaf\VendraUserProfile\Filament\Clusters\Resources` namespace, and keep plugin discovery aligned. Any future resource without a `$cluster` must instead live under `src/Filament/Resources/`.
- Keep `UserProfileResource` inside `CustomersCluster`, immediately after Users, and return `vendra-user::navigation.user_management` from `getNavigationGroup()` so Profiles and Users render together.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic. Match the surrounding file and do not add comments that restate the code.
- Add or update Pest tests for policy coverage, config/navigation behavior, translation parity, model contracts, and user-visible Filament behavior.
- Keep tests purposeful and prevent unnecessary ones: cover behavior, contracts, and edge cases — not framework internals or trivially typed code. Do not duplicate coverage a focused test already proves, and do not add throwaway verification scripts when a test fits.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus a tenant-agnostic expectation, e.g. `arch()->expect('Misaf\VendraUserProfile')->not->toUse('Misaf\VendraTenant')`.
