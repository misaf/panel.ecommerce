---
name: vendra-address-development
description: "Create, modify, review, or test the optional Vendra Address provider in packages/vendra-address, including international user-profile addresses, migrations, models, factories, and Filament relation management."
---

# Vendra Address

## Workflow

Use `laravel-best-practices` and `pest-testing` when tests change.

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep this package independently installable and coupled only one-way to `vendra-user-profile`.
- Register `addresses` dynamically and contribute UI through `UserProfileRelationManagers`; User Profile must never import this package.
- Keep `AddressPolicy`, `AddressPolicyEnum`, and `PermissionPolicySeeder` aligned for Filament strict authorization.
- Model international addresses with ISO country code and generic locality/administrative fields. Put country-specific structured fields in JSON metadata.
- Do not mark address fields translatable unless explicitly added to `$translatable`; otherwise use scalar columns and normal Filament inputs.
- Store no concrete tenant provider references; tenant ownership comes from Vendra Support.
