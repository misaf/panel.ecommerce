## Vendra Socialite

The `misaf/vendra-socialite` package adds optional OAuth social login (Google, GitHub) to Vendra user panels, built on `dutchcodingcompany/filament-socialite`. It is a one-way add-on: it depends on `misaf/vendra-user`, but `misaf/vendra-user` never depends on it.

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

### Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

- Register every table whose migration calls `TenantSchema::addTenantColumn()` with `TenantTableRegistry` in this package's service provider, preserving configured table names and connections, so `vendra-tenant:enable {tenant}` can retrofit schemas migrated before tenancy was enabled.

- Keep socialite code inside `packages/vendra-socialite` using the `Misaf\VendraSocialite` namespace.
- This module owns the `SocialiteUser` model (tenant-aware, mapping the `socialite_users` table), the `create_socialite_users_table` migration, the `Support\SocialiteRegistrar` plugin factory, and `SocialiteServiceProvider`.
- Never move socialite behaviour back into `misaf/vendra-user`; the whole point is that requiring this package — and only this package — enables social login.
- Register the `filament-socialite` plugin on the panels configured in `config/vendra-socialite.php`; do not wire it in unrelated panel providers.
- `SocialiteUser` extends the package base model and adds `BelongsToTenant`, overriding the base's `findForProvider`/`createForProvider` (which use `self`) so lookups and inserts honour the tenant scope.
- Derive tenant awareness from `misaf/vendra-support` (`BelongsToTenant`, `TenantSchema`), never a concrete tenant provider.
- Provider credentials live in the host `config/services.php` (`google`, `github`); the module only wires the buttons and account handling.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus `arch()->expect('Misaf\VendraSocialite')->not->toUse('Misaf\VendraTenant')`.
