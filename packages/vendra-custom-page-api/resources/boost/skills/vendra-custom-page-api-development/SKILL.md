---
name: vendra-custom-page-api-development
description: "Create, modify, review, or test the Vendra Custom Page JSON:API package in packages/vendra-custom-page-api, including custom page and category schemas, resources, query validation, relationships, routes, and provider wiring."
---

# Vendra Custom Page API

Use this skill together with `laravel-best-practices` and `pest-testing`.

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
- Keep filters and collection query validation synchronized, including locale-aware name/slug filters, status, soft deletes, relationships, multimedia, pagination, sparse fields, and sorting.

## Verification

- Test route registration, server schema registration, relationship endpoints, and architecture.
- Run package Pest tests, PHPStan, Composer validation, and Pint.
