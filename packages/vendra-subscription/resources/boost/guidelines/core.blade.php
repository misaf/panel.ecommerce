## Vendra Subscription

The `misaf/vendra-subscription` package orchestrates the tenant subscription lifecycle — plans, subscriptions, and tenant provisioning.

### Standards

- Keep subscription code inside `packages/vendra-subscription` using the `Misaf\VendraSubscription` namespace.
- This package owns provisioning orchestration (`Actions\CreateTenantAction`, `Actions\ProvisionTenantAction`), subscription console commands, and `SubscriptionServiceProvider`.
- This module is tenant-provisioning by design: it creates tenants and composes tenant-scoped actions from other modules (e.g. `Misaf\VendraPermission\Actions\CreateRoleAction`), so it legitimately references the tenant provider. Keep that coupling confined to provisioning.
- Prefer composing existing module actions over duplicating their logic; keep provisioning steps in `DB::transaction` and emit `Misaf\VendraSupport\Events\TenantProvisioned` for downstream listeners.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic.
- Keep tests purposeful and prevent unnecessary ones: cover provisioning behavior, transaction boundaries, and emitted events — not framework internals.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets. As a tenant-provisioning module it does not assert a `not->toUse('Misaf\VendraTenant')` expectation.
