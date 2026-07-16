## Vendra Phone

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep phone ownership in `misaf/vendra-phone`; never add phone tables or imports to `vendra-user-profile`.
- Register `phoneNumbers` dynamically and contribute UI through `UserProfileRelationManagers`.
- Keep `PhoneNumberPolicy`, `PhoneNumberPolicyEnum`, and `PermissionPolicySeeder` aligned so Filament strict authorization remains valid.
- Use `ysfkaya/filament-phone-input` with ISO country state and canonical E.164 number storage. Do not implement a competing free-text phone input.
- Preserve international flexibility through open phone type strings and metadata.
