## Vendra Socialite

The `misaf/vendra-socialite` package adds optional OAuth social login (Google, GitHub) to Vendra user panels, built on `dutchcodingcompany/filament-socialite`. It is a one-way add-on: it depends on `misaf/vendra-user`, but `misaf/vendra-user` never depends on it.

### Standards

- Keep socialite code inside `packages/vendra-socialite` using the `Misaf\VendraSocialite` namespace.
- This module owns the `SocialiteUser` model (tenant-aware, mapping the `socialite_users` table), the `create_socialite_users_table` migration, the `Support\SocialiteRegistrar` plugin factory, and `SocialiteServiceProvider`.
- Never move socialite behaviour back into `misaf/vendra-user`; the whole point is that requiring this package — and only this package — enables social login.
- Register the `filament-socialite` plugin on the panels configured in `config/vendra-socialite.php`; do not wire it in unrelated panel providers.
- `SocialiteUser` extends the package base model and adds `BelongsToTenant`, overriding the base's `findForProvider`/`createForProvider` (which use `self`) so lookups and inserts honour the tenant scope.
- Derive tenant awareness from `misaf/vendra-support` (`BelongsToTenant`, `TenantSchema`), never a concrete tenant provider.
- Provider credentials live in the host `config/services.php` (`google`, `github`); the module only wires the buttons and account handling.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus `arch()->expect('Misaf\VendraSocialite')->not->toUse('Misaf\VendraTenant')`.
