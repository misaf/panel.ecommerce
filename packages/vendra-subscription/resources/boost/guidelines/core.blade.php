## Vendra Subscription

The `misaf/vendra-subscription` package is a **generic, subscriber-agnostic subscription engine**: it owns **plans** and **subscriptions** and their lifecycle primitives, and nothing else. It does NOT know about resellers, tenants, properties, users, or provisioning — those live in the host app, which is the sole consumer.

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep subscription code inside `packages/vendra-subscription` using the `Misaf\VendraSubscription` namespace.
- This package owns `Models\{Plan,Subscription}`, `Enums\{PeriodUnit,SubscriptionStatus}`, `Exceptions\{PlanInUseException,SubscriptionPaymentException}`, and `SubscriptionServiceProvider`. Concrete plan data (`PlanSeeder`) is the host app's business config and lives in the app, not here. Depend only on `misaf/vendra-support` (for `ShouldLogActivity`) and framework/Spatie packages — never on a domain provider such as `misaf/vendra-tenant`, `misaf/vendra-user`, or `misaf/vendra-permission`.
- **Subscriber decoupling:** a `Subscription` references its owner through a plain `subscriptions.subscriber_id` column with NO Eloquent relation here — the package stays agnostic of the concrete subscriber (the host app's `Reseller`, etc.). The owning model defines the inverse `hasMany(Subscription::class, 'subscriber_id')` on its own side. `SubscriptionFactory` defaults `subscriber_id` to a random int and offers `forSubscriber($idOrModel)`. `active_subscriber_guard` (unique) enforces one active subscription per subscriber.
- `Subscription` provides generic lifecycle behavior only: scopes (`active`, `lapsed`, `expiringWithin`), `isActive()`/`isOnTrial()`/`suspendAt()`, price/currency snapshot columns, trial + grace fields. Reseller-specific orchestration (subscribe/charge/enforce/quota) lives in the host app.
- `Plan` owns pricing (`price`/`currency_code`), `trial_days`, `grace_days`, a JSON `features` array (`Plan::allows()`), and a `max_units` limit (an opaque cap the app interprets). Deleting an in-use plan throws `PlanInUseException`.
- Plan/Subscription implement `Misaf\VendraSupport\Contracts\ShouldLogActivity` (marker only). New plan/subscription columns must be reflected in the root `database/migrations` baseline byte-identically to the package `.stub` (enforced by `FreshDatabaseSchemaTest`, which also forbids `add_/rename_` follow-up migrations); cross-table references use plain indexed columns, no DB foreign keys.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic.
- Keep tests purposeful: cover plan pricing/entitlements and subscription scopes/state transitions — not framework internals or the app's reseller orchestration.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets, plus `not->toUse` for every domain provider (including `Misaf\VendraTenant`, `Misaf\VendraUser`, `Misaf\VendraPermission`) — the engine must stay subscriber-agnostic.
