## Vendra Newsletter

The `misaf/vendra-newsletter` package owns newsletters, subscribers, subscriptions, and dispatch history and the Filament admin UI for newsletters, posts, subscribers, and send history.

### Standards

- Keep newsletter domain code inside `packages/vendra-newsletter` using the `Misaf\VendraNewsletter` namespace.
- Use this package for models, migrations, factories, seeders, policies, permission enums, observers, Filament resources, translations, config, and package bootstrapping.
- Follow existing model conventions where they apply: tenant ownership, translated `name` / `description` / `slug`, soft deletes, sortable `position`, media collections, factories, and typed relationships.
- Tenant awareness is owned by `misaf/vendra-support` via the bound `TenantResolver`; consume it through `Misaf\VendraSupport\Support\TenantAwareness` and `BelongsToTenant`.
- Newsletter models, factories, and Filament UI should stay tenant-agnostic (let `BelongsToTenant` assign `tenant_id`). The send/dispatch console commands deliberately select tenants to fan out delivery; keep that tenant selection isolated to the console/queue layer and out of models and Filament resources.
- Keep Filament resources thin by delegating forms to `Schemas/*Form.php` and tables to `Tables/*Table.php`.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic. Match the surrounding file and do not add comments that restate the code.
- Add or update Pest tests for policy coverage, config/navigation behavior, translation parity, model contracts, and user-visible Filament behavior.
- Keep tests purposeful and prevent unnecessary ones: cover behavior, contracts, and edge cases — not framework internals or trivially typed code. Do not duplicate coverage a focused test already proves, and do not add throwaway verification scripts when a test fits.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus a tenant-agnostic expectation, e.g. `arch()->expect('Misaf\VendraNewsletter')->not->toUse('Misaf\VendraTenant')`.
