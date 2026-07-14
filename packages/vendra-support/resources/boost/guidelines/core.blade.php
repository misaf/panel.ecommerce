## Vendra Support

The `misaf/vendra-support` package owns the shared support layer for every Vendra module — most importantly the **tenant-awareness abstraction** that all other modules derive from.

### Standards

- Keep shared support code inside `packages/vendra-support` using the `Misaf\VendraSupport` namespace.
- This package owns the tenant contract and defaults: `Contracts\TenantResolver`, the default `NullTenantResolver` binding, `Support\TenantAwareness`, `Traits\BelongsToTenant`, `Scopes\TenantScope` / `TeamScope`, `Support\TenantSchema`, `Concerns\RequiresCurrentTenant`, `Support\TenantSeeders`, the base `Database\Seeders` (`DemoContentSeeder`, `PermissionPolicySeeder`), the base `Console\Commands` (`SeedCommand`, `TenantSeedCommand`), shared `Filament\Concerns`, and shared events/listeners.
- This package also owns optional capability contracts and null defaults, including `TagResolver`, `TagIntegration`, and `TagRelationship`. Domain consumers use these without importing the concrete provider package.
- Keep tag relationship metadata framework-level and provider-neutral. `vendra-tagger` supplies the real resolver; consumers must remain functional with `NullTagResolver`.
- Use `HasOptionalTags` as the single implementation of consumer polymorphic tag relations. Each consumer supplies its own stable type and conditionally renders UI through `TagIntegration`.
- **This is the only place tenant logic lives.** It defines the abstraction and binds `NullTenantResolver` by default (tenancy disabled). A tenant provider (e.g. `misaf/vendra-tenant`) overrides the `TenantResolver` binding to enable it.
- Never reference a concrete tenant provider such as `Misaf\VendraTenant` here. Support must build and run with no provider installed; everything derives from the bound `TenantResolver` and `TenantAwareness::enabled()`. There is no `tenant_aware` config toggle.
- `TenantSchema::addTenantColumn` adds `tenant_id` only when tenancy is enabled; `BelongsToTenant` stamps `tenant_id` on `creating` from the current tenant. Keep this the single source of tenant scoping and assignment.
- Shared Filament navigation taxonomy lives in `Misaf\VendraSupport\Filament\Navigation\NavigationGroup`. Keep group labels and ordering there; domain packages must not invent app-level group strings.
- Keep group priority in this order: Catalog, Sales, Customers, Content, Marketing, Localization, System. Register panel group labels with closures so the request locale is resolved after locale middleware runs.
- Treat each package as one top-level sidebar item: use a cluster for packages with multiple resources and top-positioned cluster sub-navigation tabs for their internal resources.
- Give every top-level package item one distinct outlined `Heroicon`; keep group headers and cluster sub-navigation items icon-free. Filament navigation groups and their child items must not both define icons.
- Assign every top-level item an explicit, unique `$navigationSort` within its group. Update `tests/Unit/AdminNavigationTest.php` whenever adding or moving navigation.
- Keep package plugin navigation-group overrides working, but default them to a `vendra-support::navigation.groups.*` key. Resolve breadcrumbs to the package label, not the broad group label.
- Browser-check the sidebar in a non-default locale after navigation changes. Do not register a navigation entry until its destination renders successfully.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus `arch()->expect('Misaf\VendraSupport')->not->toUse('Misaf\VendraTenant')`.
