---
name: vendra-verification-development
description: "Create, modify, review, or test the optional Vendra Verification provider in packages/vendra-verification, including provider-neutral user-profile verification records."
---

# Vendra Verification

## Workflow

Use `laravel-best-practices` and `pest-testing` when tests change.

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Register every table whose migration calls `TenantSchema::addTenantColumn()` with `TenantTableRegistry` in this package's service provider, preserving configured table names and connections, so `vendra-tenant:enable {tenant}` can retrofit schemas migrated before tenancy was enabled.

- Keep this package independently installable and one-way dependent on `vendra-user-profile`.
- Register `verifications` dynamically through the User Profile extension registry.
- Keep `VerificationPolicy`, `VerificationPolicyEnum`, and `PermissionPolicySeeder` aligned for Filament strict authorization.
- Keep verification types, statuses, and providers open; store jurisdiction/provider-specific details in JSON metadata.
- Do not depend on Vendra Address, Phone, or Document. The host app may orchestrate providers.
- Keep scalar fields non-translatable unless explicitly listed in `$translatable`.
