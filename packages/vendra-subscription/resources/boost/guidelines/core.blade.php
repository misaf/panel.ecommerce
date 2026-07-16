## Vendra Subscription

The `misaf/vendra-subscription` package currently orchestrates tenant provisioning. It does not provide plan, billing, or recurring-subscription models.

### Standards

- Keep subscription code inside `packages/vendra-subscription` using the `Misaf\VendraSubscription` namespace.
- This package owns provisioning orchestration (`Actions\CreateTenantAction`, `Actions\ProvisionTenantAction`), provisioning console commands, and `SubscriptionServiceProvider`.
- This module is tenant-provisioning by design: it creates tenants and composes tenant-scoped actions from other modules (e.g. `Misaf\VendraPermission\Actions\CreateRoleAction`), so it legitimately references the tenant provider. Keep that coupling confined to provisioning.
- `vendra-subscription:provision` is idempotent only with `--if-missing`: when the domain exists, report that provisioning was skipped and return success. Without the option, an existing domain remains a validation failure.
- Prefer composing existing module actions over duplicating their logic; keep provisioning steps in `DB::transaction` and emit `Misaf\VendraSupport\Events\TenantProvisioned` for downstream listeners.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic.
- Keep tests purposeful and prevent unnecessary ones: cover provisioning behavior, transaction boundaries, and emitted events — not framework internals.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets. As a tenant-provisioning module it does not assert a `not->toUse('Misaf\VendraTenant')` expectation.
