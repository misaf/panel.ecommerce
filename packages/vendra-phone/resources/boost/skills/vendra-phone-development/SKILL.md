---
name: vendra-phone-development
description: "Create, modify, review, or test the optional Vendra Phone provider in packages/vendra-phone, including international E.164 user-profile numbers and Filament Phone Input integration."
---

# Vendra Phone

## Workflow

Use `laravel-best-practices` and `pest-testing` when tests change.

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep this package independently installable and coupled only one-way to `vendra-user-profile`.
- Register `phoneNumbers` dynamically and contribute UI through `UserProfileRelationManagers`.
- Keep `PhoneNumberPolicy`, `PhoneNumberPolicyEnum`, and `PermissionPolicySeeder` aligned for Filament strict authorization.
- Use `ysfkaya/filament-phone-input`, store ISO country separately with `countryStatePath()`, and store the number in E.164 format.
- Phone fields are scalar and non-translatable; JSON metadata is only for country/carrier-specific structured information.
- Never reference a concrete tenant provider.
