## Vendra Support

The `misaf/vendra-support` package owns the shared support layer for every Vendra module — most importantly the **tenant-awareness abstraction** that all other modules derive from.

### Standards

- Keep shared support code inside `app-modules/vendra-support` using the `Misaf\VendraSupport` namespace.
- This package owns the tenant contract and defaults: `Contracts\TenantResolver`, the default `NullTenantResolver` binding, `Support\TenantAwareness`, `Traits\BelongsToTenant`, `Scopes\TenantScope` / `TeamScope`, `Support\TenantSchema`, `Concerns\RequiresCurrentTenant`, `Support\TenantSeeders`, the base `Database\Seeders` (`DemoContentSeeder`, `PermissionPolicySeeder`), the base `Console\Commands` (`SeedCommand`, `TenantSeedCommand`), shared `Filament\Concerns`, and shared events/listeners.
- **This is the only place tenant logic lives.** It defines the abstraction and binds `NullTenantResolver` by default (tenancy disabled). A tenant provider (e.g. `misaf/vendra-tenant`) overrides the `TenantResolver` binding to enable it.
- Never reference a concrete tenant provider such as `Misaf\VendraTenant` here. Support must build and run with no provider installed; everything derives from the bound `TenantResolver` and `TenantAwareness::enabled()`. There is no `tenant_aware` config toggle.
- `TenantSchema::addTenantColumn` adds `tenant_id` only when tenancy is enabled; `BelongsToTenant` stamps `tenant_id` on `creating` from the current tenant. Keep this the single source of tenant scoping and assignment.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus `arch()->expect('Misaf\VendraSupport')->not->toUse('Misaf\VendraTenant')`.
