## Vendra Document

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep document metadata ownership in `misaf/vendra-document`; User Profile exposes only an extension registry.
- Depend explicitly on `misaf/vendra-multimedia` for file persistence. Documents implement `HasMedia`, use a private single-file media collection, and must not add custom disk/path columns or raw `FileUpload` storage.
- Register `documents` dynamically and contribute UI through `UserProfileRelationManagers`.
- Keep `DocumentPolicy`, `DocumentPolicyEnum`, and `PermissionPolicySeeder` aligned so Filament strict authorization remains valid.
- Documents vary by jurisdiction: keep type open, store ISO issuing country and structured JSON metadata, and avoid country-specific columns in User Profile.
- Store uploaded documents through Vendra Multimedia on a private disk by default. Never assume public visibility.
- Document fields are scalar and non-translatable unless explicitly listed in `$translatable`.
