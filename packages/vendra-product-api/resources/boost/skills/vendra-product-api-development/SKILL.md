---
name: vendra-product-api-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Product API module in packages/vendra-product-api. Trigger for JsonApi/V1 servers, schemas, resources, collection queries, resource queries, JSON:API routes, include paths, filters, pagination, sortables, API relationships, API tests, and package service provider wiring."
---

# Vendra Product API

## Workflow

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

Always use this skill together with `laravel-best-practices` for Laravel PHP and `pest-testing` when tests are added or changed.

Before code changes, use Laravel Boost `application-info` and `search-docs` for Laravel, testing, and any API packages involved. Prefer Boost tools for route, database, and error inspection.

## Module Boundary

Treat `packages/vendra-product-api` as the JSON:API layer for `misaf/vendra-product`.

- Use namespace `Misaf\VendraProductApi`.
- Keep API servers, schemas, API resources, query validators, routes, service providers, and API tests inside this module.
- Import domain models from `Misaf\VendraProduct`; do not duplicate domain models or persistence logic in the API module.
- Keep production API code tenant-provider agnostic: inherit tenancy from the domain models and add no API tenant toggle or `Misaf\VendraTenant` reference in servers, schemas, queries, or routes. Feature tests may use a concrete tenant factory solely to establish tenant context; keep the architecture rule scoped to `Misaf\VendraProductApi`.
- Keep Filament/admin UI out of this module.
- Keep dependencies explicit in `composer.json`; do not add or change package dependencies without approval.

## JSON:API Shape

Follow the current `JsonApi/V1` layout.

- Register routes in `routes/api.php` with `JsonApiRoute::server('vendra-product')->prefix('v1')`.
- Use `JsonApiController` for standard resource endpoints.
- Keep resource type names kebab-case and stable, for example `products`, `product-categories`, `product-prices`.
- Register schemas in `JsonApi\V1\Server::allSchemas()`.
- Keep `authorizable()` behavior intentional; do not silently enable or disable authorization.
- Use schema classes for fields, relationships, filters, pagination, and sortables.

## Schema Standards

Schema classes define the public API contract.

- Use `ID::make()` and typed field classes instead of raw arrays.
- Use `ArrayHash` for translated JSON columns such as `name`, `description`, and `slug`.
- Mark generated, positional, timestamp, media, and relationship fields read-only where clients should not mutate them.
- Keep include paths explicit and minimal.
- Use `PagePagination::make()` and preserve default pagination unless product requirements change.
- Expose sortable fields with `->sortable()` and add custom sortables in `sortables()`.
- Keep relationship names aligned with the domain model methods.

## Filter And Query Standards

Keep schema filters and request validation in sync.

- Add every schema filter to the matching `ResourceQuery` or `CollectionQuery` validation rules.
- Validate `fields`, `filter`, `include`, `page`, `sort`, and `withCount` with `LaravelJsonApi\Validation\Rule` helpers.
- For translated attribute filters, use the active locale path such as `name->{$locale}` and `slug->{$locale}`.
- Use `like` filters with deserialization only for intentional partial text search.
- Use `WhereIdIn` and `WhereIdNotIn` for id inclusion and exclusion.
- Use `Has`, `WhereHas`, and `WhereDoesntHave` for relationship filters.
- Keep soft-delete filter rules aligned with actual schema behavior before exposing `with-trashed` or `only-trashed`.

## Service Provider And Routes

Keep package bootstrapping minimal and predictable.

- Use the module `ServiceProvider` for package configuration and route loading.
- Load only this module's API routes from the API module.
- Keep routes localization-package agnostic and use only Laravel's `api` middleware. Locale-aware filters read Laravel's current locale, which the host application may resolve by any mechanism.
- Do not use host-app route files for module endpoints unless integrating the package at application level.

## Testing And Verification

Use Pest tests to protect API contracts.

- Keep tests purposeful and prevent unnecessary ones: cover behavior, contracts, and edge cases — not framework internals or trivially typed code. Do not duplicate coverage a focused test already proves, and do not add throwaway verification scripts (or `tinker`) when a test fits.
- Add route tests for every new resource endpoint and relationship endpoint.
- Add server tests when schemas, resource names, or `Server` behavior changes.
- Add request validation tests when filters, includes, sparse fieldsets, sorting, pagination, or relationship filters change.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets, plus an expectation that the module stays tenant-agnostic, e.g. `arch()->expect('Misaf\VendraProductApi')->not->toUse('Misaf\VendraTenant')`. The API module may depend on `Misaf\VendraProduct`, but not on any concrete tenant provider.
- Run module checks from the package when possible: `composer --working-dir=packages/vendra-product-api test` and `composer --working-dir=packages/vendra-product-api analyse`.
- If PHP files changed, run Pint for the touched code: `vendor/bin/pint --dirty --format agent` from the host app, or the module formatter if working only inside the package.
