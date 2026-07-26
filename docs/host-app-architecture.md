# Vendra Host App — Architecture & Workflows

> A guide to how the Vendra host application (`app/`) composes ~37 `misaf/vendra-*`
> packages into a multi-tenant, reseller-billed SaaS. Intended as an
> orientation document for engineering leadership and new contributors.

---

## 1. Guiding principle

The host app is deliberately **thin**. It is the *composition root*: it wires the
domain packages together, defines the three Filament panels, and owns the
billing / reseller layer that packages intentionally leave out.

The central design bet:

> **Packages define contracts and emit events. The host composes concrete
> implementations and reacts to those events.**

Everything "above the contract line" (the subscription engine, product catalog,
etc.) is domain- and tenant-agnostic. Everything that knows about *resellers*,
*tenants*, *wallets*, or *emails* lives in the host.

---

## 2. The three Filament panels

| Panel | Domain | Guard / Broker | User model | Tenant-aware? | Purpose |
|-------|--------|----------------|-----------|---------------|---------|
| **Admin** | `<host>` | `web` / `users` | `Misaf\VendraUser\Models\User` | **Yes** | Per-tenant storefront back-office |
| **Console** | `console.<host>` | `console` / `console_users` | `App\Models\ConsoleUser` | No | Super-admin across all tenants |
| **Reseller** | `reseller.<host>` | `reseller` / `reseller_users` | `App\Models\ResellerUser` | No | Reseller self-service |

- **Admin** is the only tenant-scoped panel. Its middleware stack adds
  `EnsureAdminDomain → NeedsTenant → EnsureValidTenantSession` (Spatie
  multitenancy). It enters a single tenant's context per request.
- **Console** and **Reseller** run *outside* tenancy because they span many
  tenants. They live on dedicated subdomains and use `path('')`.
- All three share: `SetLocale` middleware (from `vendra-localization`),
  locale-aware fonts (Vazirmatn for `fa`, Google otherwise), database
  notifications, and SPA prefetching (Admin & Reseller).

Defined in:
- `app/Providers/Filament/AdminPanelServiceProvider.php`
- `app/Providers/Filament/ConsolePanelServiceProvider.php`
- `app/Providers/Filament/ResellerPanelServiceProvider.php`

---

## 3. Admin panel — package-driven navigation

The Admin panel discovers almost nothing from `app/` itself. Its resources come
from the **packages**, organized by a shared cluster taxonomy owned by
`vendra-support`:

```
CatalogCluster · SalesCluster · CustomersCluster · ContentCluster
MarketingCluster · LocalizationCluster · SystemCluster
```

- Each domain package (e.g. `vendra-product`, `vendra-blog`, `vendra-faq`)
  declares `$cluster = CatalogCluster::class` and stores its resource under
  `src/Filament/Clusters/Resources/`.
- The sidebar structure — labels, ordering, priority — is defined **once** in
  `vendra-support` via `NavigationGroup` + `NavigationPriority`.
- Result: the 7-cluster sidebar stays consistent no matter which packages are
  installed. A package registers *cluster membership*; support decides *where it
  sorts*.

The host only adds the `SpatieTranslatablePlugin` (seeded with the configured
locales from `vendra-language`).

---

## 4. Domain model — Reseller owns Tenants ("properties")

```
Reseller (App\Models\Reseller)  ── implements SubscriptionSubscriber, ShouldLogActivity
  ├─ hasMany  Tenant            ("properties" — the concrete Misaf\VendraTenant model)
  ├─ hasOne   ResellerUser      (the owner login / notification target)
  └─ morphMany Subscription     (billing periods, from vendra-subscription)
```

Key host-owned classes:

- **`App\Models\Reseller`** — the billing entity. Implements the
  `SubscriptionSubscriber` contract so the subscription engine can operate on it
  without knowing what a "property" is. Provides quota counts, property
  suspend/reactivate, subscription create/cancel/lock, and cascade offboarding
  (on delete: soft-delete properties + cancel active subscription).
- **`App\Support\PropertyQuota`** — enforces `plan->max_units` against the
  reseller's current property count; throws `SubscriptionLimitException`.
- **`App\Support\TransactionSubscriptionCharger`** — adapts the
  `SubscriptionCharger` contract to a `vendra-transaction` internal-wallet
  withdrawal (see §7).

> Only the host is allowed to reference the concrete
> `Misaf\VendraTenant\Models\Tenant`. Packages depend only on `vendra-support`
> contracts.

---

## 5. Workflow: tenant provisioning

`app/Actions/ProvisionTenantAction.php` (wraps `CreateTenantAction`):

```
ProvisionTenantAction::execute(data, reseller?)
  └─ DB::transaction
       ├─ CreateTenantAction::execute(...)
       │     ├─ validate + normalize domain (TenantDomain)
       │     ├─ (if reseller) lock reseller → PropertyQuota::assertCanCreateProperty
       │     ├─ create Tenant (reseller_id, name, slug, status)
       │     ├─ create TenantDomain (inside tenant context)
       │     └─ CreateUserAction (vendra-user) → owner User, verified
       ├─ CreateRoleAction (vendra-permission) → super-admin role
       └─ assign role to owner (inside tenant context)
  ├─ event(TenantProvisioned)          → seeding / downstream reactions
  └─ CacheTenantRoutesJob::dispatch()   → per-tenant route cache
```

Notes:
- Quota is enforced *inside the transaction*, against a **locked** reseller row,
  so concurrent property creation cannot exceed `max_units`.
- `CacheTenantRoutesJob` and other cross-tenant jobs are `NotTenantAware`
  (dispatched from off-tenant contexts).

---

## 6. Workflow: reseller creation & subscription

`app/Actions/CreateResellerAction.php`:

```
CreateResellerAction::execute(plan, username, email, password, ...)
  └─ DB::transaction
       ├─ create Reseller
       ├─ CreateResellerOwnerAction → ResellerUser (owner login)
       └─ SubscribeAction::execute(reseller, plan)   ← enters the subscription engine
```

---

## 7. Workflow: the subscription payment state machine

This is the most intricate part. The engine (`vendra-subscription`) **never
knows about tenants, resellers, notifications, or wallets** — it only emits
events. Two enums drive it:

- **`SubscriptionStatus`**: `PendingPayment → Active → PastDue → Expired / Cancelled`
- **`SubscriptionPaymentStatus`**: `Pending → Processing → Paid / Failed`
  (plus `RequiresAction`, `NeedsReconciliation`, `Cancelled`, refund states).
  `isTerminal()` = Paid / Failed / Cancelled / Refunded.

### 7.1 Collect → activate flow

**1. `SubscribeAction`** locks the subscriber, cancels any open payments / stale
pending subscriptions, then branches:

| Case | Result |
|------|--------|
| Free plan | Subscription created **`Active`** immediately, properties reactivated, `SubscriptionActivated` fired. No payment. |
| Trial (first subscription only) | Subscription **`Active`**; a payment row is created with `next_retry_at = trialEndsAt` (charged when the trial lapses). |
| Paid, no trial | Subscription **`PendingPayment`** + durable `SubscriptionPayment` row; dispatches `ProcessSubscriptionPayment` job `afterCommit()`. |

**2. `ProcessSubscriptionPayment`** (queued job)
- `ShouldBeUnique`, `tries = 5`, backoff `[5, 30, 120, 300]`.
- Must be registered in multitenancy's `not_tenant_aware_jobs` (dispatched from
  off-tenant contexts).
- Delegates to `ChargeSubscriptionAction`.

**3. `ChargeSubscriptionAction`** — the careful part:
- Claims the payment (lock → `Processing`, bump `attempt_count`) in one
  transaction, then **releases the lock before provider I/O**. It explicitly
  throws if a DB transaction is still open when calling the provider —
  *providers must not run inside a DB transaction.*
- Calls `charge()` (first attempt) or `retrieve()` (if `provider_reference`
  already set — idempotent recovery), keyed by `idempotency_key`.
- Feeds the result to `ApplySubscriptionPaymentResultAction`.

**4. `ApplySubscriptionPaymentResultAction`**
- Maps `SubscriptionChargeStatus → SubscriptionPaymentStatus`.
- Guards against provider-reference drift.
- Sets `next_retry_at = +5min` when still `Processing`.
- On **`Failed`**: transitions the subscription
  (`PendingPayment → Cancelled`, `Active → PastDue`) and fires
  `SubscriptionPaymentFailed`.
- On **`Paid`**: fires `SubscriptionPaymentPaid`.

**5. `ActivateSubscriptionOnPayment`** (in-package listener) reacts to
`SubscriptionPaymentPaid` → `ActivateSubscriptionAction`:
- Re-locks subscriber + payment + subscription, re-verifies `Paid` +
  `PendingPayment` still hold, supersedes other active subs, flips to `Active`,
  reactivates properties, fires `SubscriptionActivated`.
- **Fails loud** (throws) if the subscriber doesn't implement
  `SubscriptionSubscriber` — a paid-but-unactivatable payment is a bug, not a
  silent no-op.

**6. Failure recovery**
- Exhausted job retries → `failed()` sets status `NeedsReconciliation` +
  `next_retry_at = +15min`.
- `RecoverSubscriptionPaymentsCommand` re-drives reconciliation candidates.

### 7.2 Enforcement (scheduled)

`app/Console/Commands/EnforceSubscriptionsCommand.php`
(`vendra-subscription:enforce-subscriptions`) → `EnforceSubscriptionsAction`.
Subscriber-agnostic; touches no notifications or properties directly:

- Expires lapsed subscriptions.
- Fires `SubscriptionExpiringSoon` within a **7-day** window, marking
  `expiry_reminder_sent_at`.
- Detects past-grace subscribers → fires `SubscriptionGraceExpired`.

---

## 8. Host reactions — the contract seam

The host is where all domain-specific reactions live, wired via
**auto-discovered listeners** (do *not* also register them with `Event::listen`):

| Engine event | Host listener | Effect |
|--------------|---------------|--------|
| `SubscriptionActivated` | `NotifyActivatedSubscriber` | email the reseller owner |
| `SubscriptionExpiringSoon` | `RemindExpiringSubscriber` | email the reseller owner |
| `SubscriptionGraceExpired` | `SuspendSubscriberProperties` | suspend active properties + notify |

Two seams make this decoupling work, both bound in `app/Providers/AppServiceProvider.php`:

1. **`SubscriptionCharger`** (contract owned by `vendra-support`, null default) →
   host binds **`TransactionSubscriptionCharger`**, turning a "charge" into an
   internal-wallet `Withdrawal` transaction via `vendra-transaction`, keyed by
   the payment's idempotency key. This is why the subscription engine can charge
   money without ever importing `vendra-transaction`.

2. **`SubscriptionSubscriber`** (contract) → implemented by
   **`App\Models\Reseller`**. The engine calls `lockForSubscription()`,
   `subscribedPropertyCount()`, `reactivateSuspendedProperties()`,
   `activeSubscription()?->plan`, etc. against this interface; only the host
   knows those "units" are actually `Tenant` properties.

---

## 9. End-to-end picture

```
Console / Reseller panel  →  CreateResellerAction  →  SubscribeAction
                                                          │
                          Reseller (SubscriptionSubscriber)│ pending payment
                                                          ▼
                          ProcessSubscriptionPayment (queue, NotTenantAware)
                                                          ▼
      ChargeSubscriptionAction → TransactionSubscriptionCharger → wallet withdrawal
                                                          ▼ paid
                          SubscriptionPaymentPaid → ActivateSubscriptionAction
                                                          ▼
                          SubscriptionActivated → NotifyActivatedSubscriber (host)
                                                          ▼
                          Reseller.reactivateSuspendedProperties() → Tenants go live
```

Everything above the contract line is subscriber-agnostic engine code;
everything that knows about resellers, tenants, wallets, or emails is host code.

---

## 10. Cross-cutting wiring (`AppServiceProvider`)

- Binds `Authenticatable` → `vendra-user` `User`; overrides Filament
  reset-password / verify-email notifications with host versions.
- Morph map: `reseller`, `reseller_user`.
- Registers the settings table with `TenantTableRegistry` so tenancy can retrofit it.
- Global defaults: force HTTPS, `Model::shouldBeStrict()`, password length 8–15,
  table pagination / defer-loading defaults, Jalali-aware date pickers, panel switcher.
- **`UseRequestUrl` middleware** rewrites `app.url` / `asset_url` to the request
  host for the `console.` / `reseller.` subdomains, so asset and URL generation
  is correct off-tenant.

---

## 11. Key file map

| Concern | File |
|---------|------|
| Panels | `app/Providers/Filament/{Admin,Console,Reseller}PanelServiceProvider.php` |
| Global wiring | `app/Providers/AppServiceProvider.php` |
| Billing entity | `app/Models/Reseller.php` |
| Panel users | `app/Models/{ConsoleUser,ResellerUser}.php` |
| Quota | `app/Support/PropertyQuota.php` |
| Payment adapter | `app/Support/TransactionSubscriptionCharger.php` |
| Provisioning | `app/Actions/{ProvisionTenantAction,CreateTenantAction}.php` |
| Reseller onboarding | `app/Actions/{CreateResellerAction,CreateResellerOwnerAction}.php` |
| Event reactions | `app/Listeners/{NotifyActivatedSubscriber,RemindExpiringSubscriber,SuspendSubscriberProperties}.php` |
| Enforcement command | `app/Console/Commands/EnforceSubscriptionsCommand.php` |
| Subscription engine | `packages/vendra-subscription/src/{Actions,Jobs,Listeners,Events,Enums}` |
| Contracts / seams | `packages/vendra-support/src/Contracts/{SubscriptionCharger}.php` |
</content>
</invoke>
