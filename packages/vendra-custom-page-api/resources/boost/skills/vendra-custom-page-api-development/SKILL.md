---
name: vendra-custom-page-api-development
description: "Create, modify, review, or test the Vendra Custom Page JSON:API package in packages/vendra-custom-page-api, including custom page and category schemas, resources, query validation, relationships, routes, and provider wiring."
---

# Vendra Custom Page API

## Workflow

- Inspect `composer.json`, sibling files, and existing tests before changing the package.
- Use Laravel Boost `application-info` and `search-docs` before code changes.
- Apply `laravel-best-practices` to Laravel PHP and `pest-testing` whenever tests change.
- Apply `tailwindcss-development` only when changing Blade markup or Tailwind classes.
- Keep changes inside this package's boundary and preserve its public contracts.
- Add or update focused Pest coverage, then run `composer --working-dir=packages/vendra-custom-page-api test` and `composer --working-dir=packages/vendra-custom-page-api analyse`.

Use this skill together with `laravel-best-practices` and `pest-testing`.

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

## Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

## Module Boundary

- Keep API code in `packages/vendra-custom-page-api` under `Misaf\VendraCustomPageApi`.
- Depend on `Misaf\VendraCustomPage` models; do not duplicate models, migrations, factories, policies, or Filament UI.
- Inherit tenant scoping from domain models and never reference `Misaf\VendraTenant` in production code.
- Do not require `misaf/vendra-localization` or attach `vendra.locale`; the host application owns optional locale resolution.

## JSON:API Contract

- Register the `vendra-custom-page` server at `/v1`.
- Keep `custom-page-categories` and `custom-pages` read-only.
- Expose translated JSON columns with `ArrayHash` and serialize them with `getTranslations()`.
- Validate query-string booleans with `JsonApiRule::boolean()->asString()`.
- Keep `with-trashed` and `only-trashed` synchronized between collection queries and schemas.
- Represent the domain models' `MorphMany` multimedia relations with JSON:API `HasMany`.
- Keep `customPages`, `customPageCategory`, and `multimedia` relationship names aligned with domain methods.
- Keep filters and collection query validation synchronized, including locale-aware name/slug filters, active-state filters, soft deletes, relationships, multimedia, pagination, sparse fields, and sorting.

## Verification

- Test route registration, server schema registration, relationship endpoints, and architecture.
- Run package Pest tests, PHPStan, Composer validation, and Pint.
