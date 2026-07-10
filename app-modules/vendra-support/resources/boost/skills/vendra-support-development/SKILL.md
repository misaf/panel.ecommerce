---
name: vendra-support-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Support module in app-modules/vendra-support, or when changing anything tenant-awareness related. Trigger for TenantResolver, NullTenantResolver, TenantAwareness, BelongsToTenant, TenantScope, TeamScope, TenantSchema, RequiresCurrentTenant, TenantSeeders, base DemoContentSeeder / PermissionPolicySeeder, SeedCommand / TenantSeedCommand, shared Filament concerns, and shared events/listeners."
---

# Vendra Support

## Required Context

Always use this skill together with `modular` for module structure, `laravel-best-practices` for Laravel PHP, and `pest-testing` when tests are added or changed. Before code changes, use Laravel Boost `application-info` and `search-docs`.

## Module Boundary

Treat `app-modules/vendra-support` as the shared support and tenant-awareness core.

- Use namespace `Misaf\VendraSupport`.
- Own the tenant abstraction here and nowhere else: the `TenantResolver` contract, the default `NullTenantResolver`, `TenantAwareness`, `BelongsToTenant`, `TenantScope`/`TeamScope`, `TenantSchema`, `RequiresCurrentTenant`, `TenantSeeders`, the base seeders and seed commands, and shared Filament concerns.
- Never depend on a concrete tenant provider (`Misaf\VendraTenant`) or any domain module. Support sits at the bottom of the dependency graph and must build and run standalone.

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

## Testing And Verification

- Keep tests purposeful: cover the resolver-derived tenant contract, scopes, schema helpers, and base seeder/command behavior with tenancy both on and off.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets, plus `arch()->expect('Misaf\VendraSupport')->not->toUse('Misaf\VendraTenant')` — support must never couple to a concrete provider.
- Run module checks: `composer --working-dir=app-modules/vendra-support test` and `composer --working-dir=app-modules/vendra-support analyse`.
- If PHP files changed, run `vendor/bin/pint --dirty --format agent`.
