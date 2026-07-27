## Vendra Document

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

### Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

- Register every table whose migration calls `TenantSchema::addTenantColumn()` with `TenantTableRegistry` in this package's service provider, preserving configured table names and connections, so `vendra-tenant:enable {tenant}` can retrofit schemas migrated before tenancy was enabled.

- Keep document metadata ownership in `misaf/vendra-document`; User Profile exposes only an extension registry.
- Depend explicitly on `misaf/vendra-multimedia` for file persistence. Documents implement `HasMedia`, use a private single-file media collection, and must not add custom disk/path columns or raw `FileUpload` storage.
- Register `documents` dynamically and contribute UI through `UserProfileRelationManagers`.
- Keep `DocumentPolicy`, `DocumentPolicyEnum`, and `PermissionPolicySeeder` aligned so Filament strict authorization remains valid.
- Documents vary by jurisdiction: keep type open, store ISO issuing country and structured JSON metadata, and avoid country-specific columns in User Profile.
- Store uploaded documents through Vendra Multimedia on a private disk by default. Never assume public visibility.
- Document fields are scalar and non-translatable unless explicitly listed in `$translatable`.
