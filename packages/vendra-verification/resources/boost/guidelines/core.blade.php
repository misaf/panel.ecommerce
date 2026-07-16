## Vendra Verification

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep verification ownership in `misaf/vendra-verification`; never add verification provider fields to User Profile.
- Register `verifications` dynamically and contribute UI through `UserProfileRelationManagers`.
- Keep `VerificationPolicy`, `VerificationPolicyEnum`, and `PermissionPolicySeeder` aligned so Filament strict authorization remains valid.
- Keep type, provider, and status workflows provider-neutral. Use ISO country and JSON metadata for jurisdiction/provider-specific data.
- Verification fields are scalar and non-translatable unless explicitly declared in `$translatable`.
- Never import Address, Phone, or Document providers; cross-provider orchestration belongs in the host application.
