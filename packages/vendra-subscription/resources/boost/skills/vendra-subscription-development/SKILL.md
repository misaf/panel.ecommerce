---
name: vendra-subscription-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Subscription module in packages/vendra-subscription — a generic, subscriber-agnostic subscription engine. Trigger for the Plan and Subscription models, PeriodUnit/SubscriptionStatus enums, PlanInUseException/SubscriptionPaymentException, plan pricing/trials/entitlements, subscription scopes and lifecycle state, the subscriber_id decoupling, and subscription service-provider wiring. NOT for Reseller, quota, provisioning, PlanSeeder, or reseller actions/notifications — those live in the host app."
---

# Vendra Subscription

## Workflow

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

Always use this skill together with `laravel-best-practices` for Laravel PHP and `pest-testing` when tests are added or changed. Pair it with `vendra-support-development` when touching the `ShouldLogActivity` marker or the `SubscriptionCharger` contract. Before code changes, use Laravel Boost `application-info` and `search-docs`.

## Module Boundary

Treat `packages/vendra-subscription` as a **generic subscription engine**: plans and subscriptions and their lifecycle, with NO knowledge of the concrete subscriber.

- Use namespace `Misaf\VendraSubscription`. Own only `Models\{Plan,Subscription}`, `Enums\{PeriodUnit,SubscriptionStatus}`, `Exceptions\{PlanInUseException,SubscriptionPaymentException}`, and `SubscriptionServiceProvider`. Concrete `PlanSeeder` data is app business config and lives in the host app.
- Depend only on `misaf/vendra-support` (`ShouldLogActivity`, and the `SubscriptionCharger` contract the app implements) plus framework/Spatie packages. NEVER depend on `misaf/vendra-tenant`, `misaf/vendra-user`, `misaf/vendra-permission`, or `misaf/vendra-transaction`.
- **Subscriber decoupling:** `Subscription` links to its owner via a plain `subscriptions.subscriber_id` column with NO relation here. The owning model (the host app's `App\Models\Reseller`) defines `hasMany(Subscription::class, 'subscriber_id')`. `SubscriptionFactory::forSubscriber($idOrModel)` sets it; `active_subscriber_guard` (unique) enforces one active subscription per subscriber.
- Reseller, quota (`PropertyQuota`), provisioning (`Create/ProvisionTenantAction`), reseller actions (`Create/SubscribeResellerAction`, `ChargeSubscriptionAction`, `EnforceSubscriptionsAction`), owner notifications, and the provision/enforce commands all live in the **host app** (`app/…`), not here. Console/reseller Filament panels are app-level (`app/Filament/{Console,Reseller}`).

## Domain Standards

- `Plan` owns `price`/`currency_code`, `trial_days`, `grace_days`, `max_units` (an opaque cap the app interprets), and a JSON `features` array (`Plan::allows()`). Deleting an in-use plan throws `PlanInUseException`.
- `Subscription` provides generic lifecycle only: scopes (`active`, `lapsed`, `expiringWithin`), `isActive()`/`isOnTrial()`/`suspendAt()`, price/currency snapshot, trial + reminder fields. It implements `ShouldLogActivity` (marker).
- `SubscriptionPaymentException` is the generic payment failure (missing currency, collection failed); the app throws it from its charge flow.

## Migrations

- The root `database/migrations` baseline is the source of truth; each package migration `.stub` (`create_plans_table`, `create_subscriptions_table`) must be byte-identical to its root counterpart (enforced by `FreshDatabaseSchemaTest`), which also forbids `add_/rename_/backfill_` follow-up migrations — fold new columns into the create migration on both sides. Cross-table references (`subscriber_id`, `plan_id`) use plain indexed columns, no DB foreign keys.

## Testing And Verification

- Keep tests purposeful: cover plan pricing/entitlements, `PlanInUseException`, and subscription scopes/state transitions — not framework internals or the app's reseller orchestration (which the host app tests).
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets, plus `not->toUse` for every domain provider including `Misaf\VendraTenant`, `Misaf\VendraUser`, and `Misaf\VendraPermission` — the engine must stay subscriber-agnostic.
- Run module checks: `composer --working-dir=packages/vendra-subscription test` and `composer --working-dir=packages/vendra-subscription analyse`.
- If PHP files changed, run `vendor/bin/pint --dirty --format agent`.
