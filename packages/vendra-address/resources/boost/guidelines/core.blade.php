## Vendra Address

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep address ownership in `misaf/vendra-address`; never add address tables or provider imports to `vendra-user-profile`.
- Depend one-way on `vendra-user-profile`, register the `addresses` relation dynamically, and register the Filament relation manager through `UserProfileRelationManagers`.
- Keep `AddressPolicy`, `AddressPolicyEnum`, and `PermissionPolicySeeder` aligned so Filament strict authorization remains valid.
- Keep addresses country-adaptable: use ISO country code, locality, administrative area, sorting code, three address lines, locale, and JSON metadata for jurisdiction-specific fields.
- Address metadata is structured provider/country data, not translated content.
