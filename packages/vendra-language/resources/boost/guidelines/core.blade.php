## Vendra Language

The `misaf/vendra-language` package owns multi-language support with tenant-aware languages and database translation lines and the Filament admin UI for languages and translation lines.

### Standards

- Keep language domain code inside `packages/vendra-language` using the `Misaf\VendraLanguage` namespace.
- Use this package for models, migrations, factories, seeders, policies, permission enums, observers, Filament resources, translations, config, and package bootstrapping.
- Follow the slim `Language` model conventions: tenant ownership via `BelongsToTenant`, a catalog-validated `locale` tag (web/BCP-47), an `is_default` flag, and a sortable integer `position`. There is no stored name, slug, description, or media — display names derive from the ICU catalog through `Misaf\VendraLanguage\Support\Locales` (`symfony/intl`). A `Language` is the tenant's enabled subset of the platform catalog in `config('vendra-language.locales')`.
- When `misaf/vendra-localization` is installed, the service provider bridges the catalog into it: it feeds `supported_locales` and appends `TenantLocaleResolver` (the tenant default, lowest priority) to the resolver chain. Keep this optional and guarded — never hard-require localization, and never make `vendra-tenant` depend on this module.
- Tenant awareness is owned by `misaf/vendra-support` via `Misaf\VendraSupport\Support\TenantAwareness`, which derives purely from the bound `TenantResolver`. Installing a tenant provider (e.g. `misaf/vendra-tenant`) makes the app tenant-aware; without one the default null resolver keeps it disabled. The module defines no `tenant_aware` config.
- Keep the module tenant-agnostic: it must build and run with or without a tenant provider. Never reference a concrete provider such as `Misaf\VendraTenant` anywhere — models, migrations, factories, seeders, or fixtures. Let `BelongsToTenant` assign `tenant_id`; do not set it manually.
- Keep Filament resources thin by delegating forms to `Schemas/*Form.php` and tables to `Tables/*Table.php`.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic. Match the surrounding file and do not add comments that restate the code.
- Add or update Pest tests for policy coverage, config/navigation behavior, translation parity, model contracts, and user-visible Filament behavior.
- Keep tests purposeful and prevent unnecessary ones: cover behavior, contracts, and edge cases — not framework internals or trivially typed code. Do not duplicate coverage a focused test already proves, and do not add throwaway verification scripts when a test fits.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus a tenant-agnostic expectation, e.g. `arch()->expect('Misaf\VendraLanguage')->not->toUse('Misaf\VendraTenant')`.
