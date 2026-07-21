---
name: vendra-subscription-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Subscription module in packages/vendra-subscription. Trigger for Account, Plan, Subscription models, WebsiteQuota, SubscriptionLimitException, CreateAccountAction, SubscribeAccountAction, ChargeSubscriptionAction, EnforceSubscriptionsAction, CreateTenantAction, ProvisionTenantAction, plan pricing/trials/entitlements, subscription lifecycle enforcement, owner notifications, the provision and enforce-subscriptions commands, PlanSeeder, and subscription service-provider wiring."
---

# Vendra Subscription

## Workflow

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

Always use this skill together with `laravel-best-practices` for Laravel PHP and `pest-testing` when tests are added or changed. Pair it with `vendra-tenant-development`, `vendra-permission-development`, and `vendra-support-development` when working across those modules. Before code changes, use Laravel Boost `application-info` and `search-docs`.

## Module Boundary

Treat `packages/vendra-subscription` as the billing domain: accounts, plans, subscriptions, quota/lifecycle enforcement, plus the original tenant-provisioning orchestration.

- Use namespace `Misaf\VendraSubscription`.
- `Account` (billing entity) owns websites (`Tenant`s) via `tenants.account_id`, `hasOne ownerUser` via `users.account_id`, and holds one active `Subscription` to a `Plan`. The `Account → Tenant` relation lives only on `Account`; never add `account()` to `Tenant` (avoids a `vendra-tenant → vendra-subscription` cycle).
- This module is intentionally tenant-coupled: it references `Misaf\VendraTenant\Models\{Tenant,TenantDomain}` and composes permission/user/tenant actions. Keep cross-module dependencies explicit in `composer.json`. Do NOT depend on `misaf/vendra-transaction` — payment collection goes through the `Misaf\VendraSupport\Contracts\SubscriptionCharger` capability contract, which the host app implements.
- Platform (superadmin) and account (self-service) Filament panels/resources are app-level (`app/Filament/{Platform,Account}`), not in this package.

## Domain Standards

- One active subscription per account. `SubscribeAccountAction` cancels the active subscription, snapshots `price`/`currency_code`, sets `trial_ends_at` only on the first subscription, reactivates suspended websites, then charges and notifies post-commit. It throws `SubscriptionLimitException::planBelowUsage` when the plan cannot hold current websites.
- Quota via `Support\WebsiteQuota` (active subscription + `max_websites`). Website creation flows through `CreateTenantAction`/`ProvisionTenantAction` with an `Account`, which enforce quota and stamp `account_id` (the first website's owner also becomes the account owner).
- Lifecycle via `EnforceSubscriptionsAction` + `vendra-subscription:enforce-subscriptions` (scheduled daily in the app): expire lapsed subscriptions, remind owners once before expiry (`expiry_reminder_sent_at`), and suspend websites past `ends_at`+`grace_days`. Offboarding cascade is in `Account::booted()`.
- Payments: `ChargeSubscriptionAction` resolves `SubscriptionCharger` + `Account::ownerUser` and no-ops for free plans, trials, missing owners, or an unavailable charger. Test with a fake charger bound via `app()->instance(SubscriptionCharger::class, ...)`.
- Owner notifications live in `src/Notifications` and are plain (synchronous), NOT `ShouldQueue` — queued notifications crash on the sync test queue via Spatie's tenant-aware-job wrapper. For async, use a `NotTenantAware` job.
- `Account`/`Plan`/`Subscription` implement `ShouldLogActivity` (marker). Plans carry `price`/`currency_code`, `trial_days`, and a JSON `features` array (`Plan::allows()`, `Account::allows()`).

## Provisioning Standards

- Wrap multi-step provisioning in `DB::transaction`; emit `Misaf\VendraSupport\Events\TenantProvisioned` and let listeners handle seeding/caching.
- Generate credentials safely; never log secrets.
- `vendra-subscription:provision` is idempotent only with `--if-missing`; `--account`/`--plan` bind the website to an account. Keep artisan-driven steps (route caching) idempotent and their failures surfaced.

## Migrations

- The root `database/migrations` baseline is the source of truth; every package migration `.stub` must be byte-identical to its root counterpart (enforced by `FreshDatabaseSchemaTest`), which also forbids `add_/rename_/backfill_` follow-up migrations — fold new columns into the create migration on both sides. Cross-table references (`account_id`, `plan_id`) use plain indexed columns, no DB foreign keys.

## Testing And Verification

- Keep tests purposeful: cover quota + downgrade guards, lifecycle transitions (expire/suspend/reactivate), charging via a fake `SubscriptionCharger`, trials/entitlements, provisioning rollback, and event emission — not framework internals.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets. Do not assert `not->toUse('Misaf\VendraTenant')` — the module intentionally references the tenant provider.
- Run module checks: `composer --working-dir=packages/vendra-subscription test` and `composer --working-dir=packages/vendra-subscription analyse`.
- If PHP files changed, run `vendor/bin/pint --dirty --format agent`.
