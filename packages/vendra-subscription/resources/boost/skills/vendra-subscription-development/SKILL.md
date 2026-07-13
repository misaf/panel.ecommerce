---
name: vendra-subscription-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Subscription module in packages/vendra-subscription. Trigger for CreateTenantAction, ProvisionTenantAction, tenant provisioning orchestration, subscription/plan lifecycle, subscription console commands, TenantProvisioned events, and subscription service-provider wiring."
---

# Vendra Subscription

## Required Context

Always use this skill together with `modular` for module structure, `laravel-best-practices` for Laravel PHP, and `pest-testing` when tests are added or changed. Pair it with `vendra-tenant-development` and `vendra-permission-development` when provisioning composes those modules. Before code changes, use Laravel Boost `application-info` and `search-docs`.

## Module Boundary

Treat `packages/vendra-subscription` as tenant subscription and provisioning orchestration.

- Use namespace `Misaf\VendraSubscription`.
- Own provisioning actions, subscription lifecycle, and related console commands here.
- This module is intentionally tenant-coupled: it provisions tenants and may reference the tenant provider directly. Keep that coupling inside provisioning code, not spread into unrelated logic.
- Compose other modules' actions (permission, user, tenant) rather than reimplementing their behavior; keep cross-module dependencies explicit in `composer.json`.

## Provisioning Standards

- Wrap multi-step provisioning in `DB::transaction` so a partial failure rolls back cleanly.
- Emit `Misaf\VendraSupport\Events\TenantProvisioned` and let listeners handle post-provisioning work (seeding, caching) rather than inlining it.
- Generate credentials and secrets safely; never log secrets.
- Keep artisan-driven steps (route caching, per-tenant commands) idempotent and their failures surfaced.

## Testing And Verification

- Keep tests purposeful: cover the provisioning happy path, rollback on failure, and event emission — not framework internals.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets. Do not assert `not->toUse('Misaf\VendraTenant')` — provisioning intentionally references the tenant provider.
- Run module checks: `composer --working-dir=packages/vendra-subscription test` and `composer --working-dir=packages/vendra-subscription analyse`.
- If PHP files changed, run `vendor/bin/pint --dirty --format agent`.
