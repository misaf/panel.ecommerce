---
name: vendra-subscription-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Subscription module in packages/vendra-subscription — a generic, subscriber-agnostic subscription engine. Trigger for the Plan and Subscription models, PeriodUnit/SubscriptionStatus enums, PlanInUseException/SubscriptionPaymentException, plan pricing/trials/entitlements, subscription scopes and lifecycle state, the polymorphic subscriber contract, and subscription service-provider wiring. NOT for Reseller, quota, provisioning, PlanSeeder, or reseller actions/notifications — those live in the host app."
---

# Vendra Subscription

## Workflow

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

## Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

Always use this skill together with `laravel-best-practices` for Laravel PHP and `pest-testing` when tests are added or changed. Pair it with `vendra-support-development` when touching the `ShouldLogActivity` marker or the `SubscriptionCharger` contract. Before code changes, use Laravel Boost `application-info` and `search-docs`.

## Module Boundary

Treat `packages/vendra-subscription` as a **generic subscription engine**: plans and subscriptions and their lifecycle, with NO knowledge of the concrete subscriber.

- Use namespace `Misaf\VendraSubscription`. Own only `Models\{Plan,Subscription,SubscriptionPayment}`, `Enums\{PeriodUnit,SubscriptionStatus,SubscriptionPaymentStatus}`, `Exceptions\{PlanInUseException,SubscriptionPaymentException}`, and `SubscriptionServiceProvider`. Concrete `PlanSeeder` data is app business config and lives in the host app.
- Depend only on `misaf/vendra-support` (`ShouldLogActivity`, and the `SubscriptionCharger` contract the app implements) plus framework/Spatie packages. NEVER depend on `misaf/vendra-tenant`, `misaf/vendra-user`, `misaf/vendra-permission`, or `misaf/vendra-transaction`.
- **Subscriber decoupling:** `Subscription::subscriber()` is a generic `MorphTo` over `subscriber_type` / `subscriber_id`; never import a concrete subscriber here. Owning models define `morphMany`. Each host registers stable morph aliases and migrates pre-alias FQCN values. `SubscriptionFactory::forSubscriber($model)` stores the model's morph class, while a bare ID uses the explicit generic default type. The `(subscriber_type, active_subscriber_guard)` unique index enforces one active subscription per subscriber.
- Reseller, quota (`PropertyQuota`), provisioning (`Create/ProvisionTenantAction`), reseller actions (`Create/SubscribeResellerAction`, `ChargeSubscriptionAction`, `EnforceSubscriptionsAction`), owner notifications, and the provision/enforce commands all live in the **host app** (`app/…`), not here. Console/reseller Filament panels are app-level (`app/Filament/{Console,Reseller}`).

## Domain Standards

- `Plan` owns `price`/`currency_code`, `trial_days`, `grace_days`, `max_units` (an opaque cap the app interprets), and a JSON `features` array (`Plan::allows()`). Deleting an in-use plan throws `PlanInUseException`.
- `Subscription` provides generic lifecycle only: scopes (`active`, `lapsed`, `expiringWithin`), `isActive()`/`isOnTrial()`/`suspendAt()`, price/currency snapshot, trial + reminder fields. Paid periods use `PendingPayment` until collection succeeds. It implements `ShouldLogActivity` (marker).
- `SubscriptionPayment` is the durable, provider-neutral payment operation. Keep its provider, immutable idempotency key, provider reference, amount/currency snapshot, status, attempt count, sanitized failure details, and recovery timestamps auditable. A pending transaction is not a successful collection.
- `SubscriptionPaymentException` is the generic payment failure (missing currency, collection failed); the app throws it from its charge flow.
- Treat `SubscriptionCharge::reference` as an idempotency key. `SubscriptionCharger::charge()` and `retrieve()` return typed lifecycle results. Providers must resolve repeated identical operations to the original outcome, never collect twice, and reject reuse for different financial details. Host orchestration performs provider I/O only after commit and outside every database transaction or row lock.

## Migrations

- The root `database/migrations` baseline is the source of truth; each package migration `.stub` (`create_plans_table`, `create_subscriptions_table`) must be byte-identical to its root counterpart (enforced by `FreshDatabaseSchemaTest`), which also forbids `add_/rename_/backfill_` schema follow-ups — fold new columns into the create migration on both sides. Host-only data migrations may normalize persisted morph types because the generic package cannot own concrete aliases. Cross-table references (`subscriber_id`, `plan_id`) use plain indexed columns, no DB foreign keys.

## Testing And Verification

- Keep tests purposeful: cover plan pricing/entitlements, `PlanInUseException`, and subscription scopes/state transitions — not framework internals or the app's reseller orchestration (which the host app tests).
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets, plus `not->toUse` for every domain provider including `Misaf\VendraTenant`, `Misaf\VendraUser`, and `Misaf\VendraPermission` — the engine must stay subscriber-agnostic.
- Run module checks: `composer --working-dir=packages/vendra-subscription test` and `composer --working-dir=packages/vendra-subscription analyse`.
- If PHP files changed, run `vendor/bin/pint --dirty --format agent`.
