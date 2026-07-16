---
name: vendra-support-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Support module in packages/vendra-support, changing tenant awareness, optional capability resolvers, or shared Filament sidebar navigation. Trigger for TenantResolver, TagResolver, TagIntegration, NullTenantResolver, TenantAwareness, BelongsToTenant, TenantScope, TeamScope, TenantSchema, RequiresCurrentTenant, TenantSeeders, shared seeders and commands, Filament NavigationGroup taxonomy, navigation groups, package sidebar priority, package icons, clusters, sub-navigation, shared Filament concerns, shared events/listeners, ResolvesPolicyPermissions, the composable policy ability traits (Authorizes*Abilities), and permission policy enum conventions."
---

# Vendra Support

## Workflow

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

Always use this skill together with `laravel-best-practices` for Laravel PHP and `pest-testing` when tests are added or changed. Before code changes, use Laravel Boost `application-info` and `search-docs`.

## Module Boundary

Treat `packages/vendra-support` as the shared support and tenant-awareness core.

- Use namespace `Misaf\VendraSupport`.
- Own the tenant abstraction here and nowhere else: the `TenantResolver` contract, the default `NullTenantResolver`, `TenantAwareness`, `BelongsToTenant`, `TenantScope`/`TeamScope`, `TenantSchema`, `RequiresCurrentTenant`, `TenantSeeders`, the base seeders and seed commands, shared Filament concerns, and the shared policy authorization concerns (`ResolvesPolicyPermissions` plus the `Authorizes*Abilities` traits).
- Never depend on a concrete tenant provider (`Misaf\VendraTenant`) or any domain module. Support sits at the bottom of the dependency graph and must build and run standalone.
- Own small optional-provider boundaries here. Tag consumers use `TagResolver`, `TagIntegration`, and `TagRelationship`; the concrete Tagger module binds the available resolver.
- Keep `TagRelationship` limited to Eloquent polymorphic relationship metadata. Do not leak Spatie Tags or a domain model into Support.
- Keep the reusable consumer relation in `HasOptionalTags`; require each model to return a stable domain-specific type and keep unavailable-provider failure behavior consistent.

## Tenant Abstraction Rules

Tenant awareness is derived purely from the bound resolver — never from config.

- `TenantAwareness::enabled()` returns `app(TenantResolver::class)->available()`. The default `NullTenantResolver` reports unavailable, so tenancy is off until a provider binds a real resolver.
- `BelongsToTenant` adds `TenantScope` + `TeamScope` and a `creating` hook that stamps `tenant_id` only when `TenantSchema::hasTenantColumn()` and a tenant is current. Do not assign `tenant_id` manually anywhere.
- `TenantSchema::addTenantColumn` / `tenantIndex` add the column and composite indexes only when tenancy is enabled, so migrations stay valid with tenancy off.
- Extend the contract deliberately: keep `TenantResolver` minimal and make sure both `NullTenantResolver` and any provider resolver implement every method.
- `RequiresCurrentTenant` offers `currentTenant()` (strict) and `currentTenantOrNull()` (null when tenancy is disabled) — pick the strict one only where a tenant is genuinely required.

## Shared Building Blocks

- Base `DemoContentSeeder` / `PermissionPolicySeeder` and `SeedCommand` / `TenantSeedCommand` must run tenant-agnostically: seed globally when tenancy is off, per-tenant (optional `{tenant?}` arg) when on.
- Keep shared Filament concerns generic and free of any single module's domain assumptions.

## Policy Authorization Concerns

Domain policies across all modules compose the shared ability traits instead of hand-writing per-ability methods.

- `Concerns\ResolvesPolicyPermissions` declares abstract `permissionEnum(): string` (the module's `*PolicyEnum`) and resolves enum cases by ability name; a composed ability with no matching case throws at call time — keep that fail-loud behavior.
- The ability traits are `AuthorizesViewAbilities` (view, viewAny), `AuthorizesCreateAbilities`, `AuthorizesUpdateAbilities`, `AuthorizesDeleteAbilities` (delete, deleteAny), `AuthorizesForceDeleteAbilities`, `AuthorizesRestoreAbilities`, `AuthorizesReplicateAbilities`, and `AuthorizesReorderAbilities`.
- Compose whole traits only. Never adopt a trait whose abilities the resource does not grant; hard denies (`return false`) and model-state rules stay explicit class methods, and custom abilities (approve, send, process) call `$this->allowed($user, 'Case')` directly.
- Policy permission enums use TitleCase cases named exactly after the ability (`ViewAny`, `ForceDelete`) with kebab-case permission values (`view-any-attribute`); the resolver matches case names to abilities, so names must not drift.
- Every policy also keeps `use AuthorizesSandboxMode;` — the root `PackagePolicySandboxModeTest` enforces it.

## Filament Navigation Architecture

Use `Misaf\VendraSupport\Filament\Navigation\NavigationGroup` as the single source of navigation group labels and `NavigationPriority` as the single source of resource ordering. Do not add app translation strings as package group defaults.

- Register groups in the admin panel as `Filament\Navigation\NavigationGroup` objects with label closures. Do not pass the enum class directly: the locale middleware runs after panel construction, so eager labels can be cached in the wrong locale.
- Store package resources that declare a `$cluster` under `src/Filament/Clusters/Resources/` with matching `Filament\Clusters\Resources` namespaces. Store resources without a cluster under `src/Filament/Resources/`, and keep plugin discovery paths aligned.
- Keep group order: Catalog, Sales, Customers, Content, Marketing, Localization, System.
- Keep current item order:
  - Catalog: Products 1, Attributes 2.
  - Sales: Transactions 1 when enabled, Currencies 2, Carts 3.
  - Customers: Users 1, User Profiles 2, Roles 3, Permissions 4.
  - Content: Blog 1, Custom Pages 2, FAQs 3, Multimedia 4, Tags 5.
  - Marketing: Affiliates 1, Newsletters 2.
  - Localization: Languages 1.
  - System: Settings 1, Activity Logs 2, Authentication Logs 3.
- Give every resource a globally unique `NavigationPriority` case and assign `$navigationSort` from its backed value. Group values by domain cluster and leave gaps for future resources.
- Give every resource separate singular and plural translation keys in `en`, `de`, and `fa`. Use the singular key for model labels and the plural key for navigation and plural model labels; keep navigation labels at 24 characters or fewer.
- Use domain clusters as top-level sidebar items, set `$subNavigationPosition = SubNavigationPosition::Top`, and keep cluster resources ungrouped so `NavigationPriority` controls their visible tab order.
- Set one distinct outlined `Heroicon` on every top-level cluster and resource. Keep navigation group headers icon-free.
- Preserve package plugin `navigationGroup(...)` overrides and default them to `vendra-support::navigation.groups.*`.
- Use the package label for its cluster breadcrumb. Never use the broad group label as the breadcrumb.
- Update `tests/Unit/AdminNavigationTest.php` for group order, locale-safe labels, item sort, icons, and top sub-navigation.
- Verify the rendered sidebar in at least one non-default locale and open each new destination. Do not expose incomplete or failing package UI.

## Testing And Verification

- Keep tests purposeful: cover the resolver-derived tenant contract, scopes, schema helpers, and base seeder/command behavior with tenancy both on and off.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets, plus `arch()->expect('Misaf\VendraSupport')->not->toUse('Misaf\VendraTenant')` — support must never couple to a concrete provider.
- Run module checks: `composer --working-dir=packages/vendra-support test` and `composer --working-dir=packages/vendra-support analyse`.
- If PHP files changed, run `vendor/bin/pint --dirty --format agent`.
