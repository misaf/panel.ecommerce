## Vendra FAQ

The `misaf/vendra-faq` package owns frequently asked questions with categorization and the Filament admin UI for FAQ entries and categories.

### Standards

- Keep faq domain code inside `packages/vendra-faq` using the `Misaf\VendraFaq` namespace.
- Use this package for models, migrations, factories, seeders, policies, permission enums, observers, Filament resources, translations, config, and package bootstrapping.
- Keep JSON:API serialization and API routes in `misaf/vendra-faq-api`.
- Follow the concrete models and neighboring files in this package; do not apply translation, media, slug, sorting, or soft-delete patterns unless the affected model already uses them.
- Tenant awareness is owned by `misaf/vendra-support` via `Misaf\VendraSupport\Support\TenantAwareness`, which derives purely from the bound `TenantResolver`. Installing a tenant provider (e.g. `misaf/vendra-tenant`) makes the app tenant-aware; without one the default null resolver keeps it disabled. The module defines no `tenant_aware` config.
- Keep the module tenant-agnostic: it must build and run with or without a tenant provider. Never reference a concrete provider such as `Misaf\VendraTenant` anywhere — models, migrations, factories, seeders, or fixtures. Let `BelongsToTenant` assign `tenant_id`; do not set it manually.
- Keep Filament resources thin by delegating forms to `Schemas/*Form.php` and tables to `Tables/*Table.php`.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic. Match the surrounding file and do not add comments that restate the code.
- Integrate optional FAQ tags only through `HasOptionalTags` and `TagIntegration` from `misaf/vendra-support`. Reserve the `faq` type; never import Vendra Tagger or Spatie Tags, and keep Tagger in Composer `suggest` rather than `require`.
- Add or update Pest tests for policy coverage, config/navigation behavior, translation parity, model contracts, and user-visible Filament behavior.
- Keep tests purposeful and prevent unnecessary ones: cover behavior, contracts, and edge cases — not framework internals or trivially typed code. Do not duplicate coverage a focused test already proves, and do not add throwaway verification scripts when a test fits.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus a tenant-agnostic expectation, e.g. `arch()->expect('Misaf\VendraFaq')->not->toUse('Misaf\VendraTenant')`.
