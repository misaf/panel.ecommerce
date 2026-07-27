---
name: vendra-document-development
description: "Create, modify, review, or test the optional Vendra Document provider in packages/vendra-document, including private jurisdiction-aware user-profile documents."
---

# Vendra Document

## Workflow

- Inspect `composer.json`, sibling files, and existing tests before changing the package.
- Use Laravel Boost `application-info` and `search-docs` before code changes.
- Apply `laravel-best-practices` to Laravel PHP and `pest-testing` whenever tests change.
- Apply `tailwindcss-development` only when changing Blade markup or Tailwind classes.
- Keep changes inside this package's boundary and preserve its public contracts.
- Add or update focused Pest coverage, then run `composer --working-dir=packages/vendra-document test` and `composer --working-dir=packages/vendra-document analyse`.

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

## Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

- Register every table whose migration calls `TenantSchema::addTenantColumn()` with `TenantTableRegistry` in this package's service provider, preserving configured table names and connections, so `vendra-tenant:enable {tenant}` can retrofit schemas migrated before tenancy was enabled.

- Keep this package independently installable and dependent only on `vendra-user-profile`, `vendra-multimedia`, and shared support infrastructure.
- Register `documents` dynamically through the User Profile extension registry.
- Keep `DocumentPolicy`, `DocumentPolicyEnum`, and `PermissionPolicySeeder` aligned for Filament strict authorization.
- Keep document type open, store ISO issuing country, and use JSON metadata for jurisdiction-specific structured fields.
- Use Vendra Multimedia's Spatie Media Library integration with a private single-file collection. Do not restore custom `disk` or `path` columns or a raw Filament `FileUpload`.
- Use private file visibility and never expose identity documents through public storage by default.
- Do not make scalar document fields translatable unless the model explicitly declares them.
