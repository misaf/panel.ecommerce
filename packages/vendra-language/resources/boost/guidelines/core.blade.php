## Vendra Language

The `misaf/vendra-language` package owns multi-language support with tenant-aware languages and database translation lines and the Filament admin UI for languages and translation lines.

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep language domain code inside `packages/vendra-language` using the `Misaf\VendraLanguage` namespace.
- Use this package for models, migrations, factories, seeders, policies, permission enums, observers, Filament resources, translations, config, and package bootstrapping.
- Follow the slim `Language` model conventions: tenant ownership via `BelongsToTenant`, a catalog-validated `locale` tag (web/BCP-47), an `is_default` flag, and a sortable integer `position`. There is no stored name, slug, description, or media — display names derive from the ICU catalog through `Misaf\VendraLanguage\Support\Locales` (`symfony/intl`). A `Language` is the tenant's enabled subset of the platform catalog in `config('vendra-language.locales')`.
- Keep package translation synchronization centralized in `TranslationCatalog` and `SyncLanguageLines`. Discover only registered `vendra-*` translation namespaces from the Laravel file loader, flatten nested PHP translation arrays to dot-notation keys, and collect every locale provided by each installed package.
- Treat package files as import defaults and database language lines as user-owned overrides. Synchronization may create missing lines and add locale keys that are absent from an existing line, but it must never replace an existing database locale value, including an intentionally blank value. Keep synchronization idempotent, tenant-scoped through `BelongsToTenant`, and transaction-safe.
- Expose synchronization from both Languages and Language Lines list pages through the shared Filament action. Because it can create and update records, require both Language Line create and update authorization and report created, updated, and unchanged counts.
- When `misaf/vendra-localization` is installed, the service provider bridges the catalog into it: it feeds `supported_locales` and appends `TenantLocaleResolver` (the tenant default, lowest priority) to the resolver chain. Keep this optional and guarded — never hard-require localization, and never make `vendra-tenant` depend on this module.
- Tenant awareness is owned by `misaf/vendra-support` via `Misaf\VendraSupport\Support\TenantAwareness`, which derives purely from the bound `TenantResolver`. Installing a tenant provider (e.g. `misaf/vendra-tenant`) makes the app tenant-aware; without one the default null resolver keeps it disabled. The module defines no `tenant_aware` config.
- Keep the module tenant-agnostic: it must build and run with or without a tenant provider. Never reference a concrete provider such as `Misaf\VendraTenant` anywhere — models, migrations, factories, seeders, or fixtures. Let `BelongsToTenant` assign `tenant_id`; do not set it manually.
- Keep Filament resources thin by delegating forms to `Schemas/*Form.php` and tables to `Tables/*Table.php`.
- Because the package resources declare a `$cluster`, keep their complete resource trees under `src/Filament/Clusters/Resources/`, use the matching `Misaf\VendraLanguage\Filament\Clusters\Resources` namespace, and keep plugin discovery aligned. Any future resource without a cluster must instead live under `src/Filament/Resources/`.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic. Match the surrounding file and do not add comments that restate the code.
- Add or update Pest tests for policy coverage, config/navigation behavior, translation parity, model contracts, and user-visible Filament behavior.
- Cover translation synchronization with focused tests for nested keys, all discovered package locales, preservation of customized and blank database values, missing-locale merging, idempotency, and action availability.
- Keep tests purposeful and prevent unnecessary ones: cover behavior, contracts, and edge cases — not framework internals or trivially typed code. Do not duplicate coverage a focused test already proves, and do not add throwaway verification scripts when a test fits.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus a tenant-agnostic expectation, e.g. `arch()->expect('Misaf\VendraLanguage')->not->toUse('Misaf\VendraTenant')`.
