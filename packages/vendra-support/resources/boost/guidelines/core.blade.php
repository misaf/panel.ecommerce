## Vendra Support

The `misaf/vendra-support` package owns the shared support layer for every Vendra module — most importantly the **tenant-awareness abstraction** that all other modules derive from.

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep shared support code inside `packages/vendra-support` using the `Misaf\VendraSupport` namespace.
- This package owns the tenant contract and defaults: `Contracts\TenantResolver`, the default `NullTenantResolver` binding, `Support\TenantAwareness`, `Traits\BelongsToTenant`, `Scopes\TenantScope` / `TeamScope`, `Support\TenantSchema`, `Concerns\RequiresCurrentTenant`, `Support\TenantSeeders`, the base `Database\Seeders` (`DemoContentSeeder`, `PermissionPolicySeeder`), the base `Console\Commands` (`SeedCommand`, `TenantSeedCommand`), shared `Filament\Concerns`, and shared events/listeners.
- This package also owns optional capability contracts and null defaults, including `TagResolver`, `TagIntegration`, and `TagRelationship`. Domain consumers use these without importing the concrete provider package.
- This package owns the shared policy authorization concerns: `Concerns\ResolvesPolicyPermissions` plus the composable `Concerns\Authorizes*Abilities` traits (View, Create, Update, Delete, ForceDelete, Restore, Replicate, Reorder). Domain policies compose whole ability traits, implement `permissionEnum()` returning the module's `*PolicyEnum`, and keep hard denies or model-state rules as explicit methods — never adopt a trait whose abilities the resource does not grant.
- Policy permission enums use TitleCase cases named after the ability (`ViewAny`, `ForceDelete`) with kebab-case permission values (`view-any-attribute`); `ResolvesPolicyPermissions` resolves cases by ability name, so case names must match.
- Keep tag relationship metadata framework-level and provider-neutral. `vendra-tagger` supplies the real resolver; consumers must remain functional with `NullTagResolver`.
- Use `HasOptionalTags` as the single implementation of consumer polymorphic tag relations. Each consumer supplies its own stable type and conditionally renders UI through `TagIntegration`.
- **This is the only place tenant logic lives.** It defines the abstraction and binds `NullTenantResolver` by default (tenancy disabled). A tenant provider (e.g. `misaf/vendra-tenant`) overrides the `TenantResolver` binding to enable it.
- Never reference a concrete tenant provider such as `Misaf\VendraTenant` here. Support must build and run with no provider installed; everything derives from the bound `TenantResolver` and `TenantAwareness::enabled()`. There is no `tenant_aware` config toggle.
- `TenantSchema::addTenantColumn` adds `tenant_id` only when tenancy is enabled; `BelongsToTenant` stamps `tenant_id` on `creating` from the current tenant. Keep this the single source of tenant scoping and assignment.
- Shared Filament navigation taxonomy lives in `Misaf\VendraSupport\Filament\Navigation\NavigationGroup`. Keep group labels and ordering there; domain packages must not invent app-level group strings.
- Store every package resource that declares a `$cluster` under `src/Filament/Clusters/Resources/` with a matching `Filament\Clusters\Resources` namespace. Store resources without a cluster under `src/Filament/Resources/`.
- Keep group priority in this order: Catalog, Sales, Customers, Content, Marketing, Localization, System. Register panel group labels with closures so the request locale is resolved after locale middleware runs.
- Treat each package as one top-level sidebar item: use a cluster for packages with multiple resources and top-positioned cluster sub-navigation tabs for their internal resources. In `CustomersCluster`, group Users and User Profiles together under `vendra-user::navigation.user_management`.
- Give every top-level package item one distinct outlined `Heroicon`; keep group headers and cluster sub-navigation items icon-free. Filament navigation groups and their child items must not both define icons.
- Assign every top-level item an explicit, unique `$navigationSort` within its group. Update `tests/Unit/AdminNavigationTest.php` whenever adding or moving navigation.
- Keep package plugin navigation-group overrides working, but default them to a `vendra-support::navigation.groups.*` key. Resolve breadcrumbs to the package label, not the broad group label.
- Browser-check the sidebar in a non-default locale after navigation changes. Do not register a navigation entry until its destination renders successfully.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus `arch()->expect('Misaf\VendraSupport')->not->toUse('Misaf\VendraTenant')`.
