---
name: vendra-document-development
description: "Create, modify, review, or test the optional Vendra Document provider in packages/vendra-document, including private jurisdiction-aware user-profile documents."
---

# Vendra Document

## Workflow

Use `laravel-best-practices` and `pest-testing` when tests change.

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep this package independently installable and dependent only on `vendra-user-profile`, `vendra-multimedia`, and shared support infrastructure.
- Register `documents` dynamically through the User Profile extension registry.
- Keep `DocumentPolicy`, `DocumentPolicyEnum`, and `PermissionPolicySeeder` aligned for Filament strict authorization.
- Keep document type open, store ISO issuing country, and use JSON metadata for jurisdiction-specific structured fields.
- Use Vendra Multimedia's Spatie Media Library integration with a private single-file collection. Do not restore custom `disk` or `path` columns or a raw Filament `FileUpload`.
- Use private file visibility and never expose identity documents through public storage by default.
- Do not make scalar document fields translatable unless the model explicitly declares them.
