---
name: vendra-blog-api-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Blog API module in app-modules/vendra-blog-api, or when creating future API modules that expose domain modules through Laravel JSON:API. Trigger for JsonApi/V1 servers, schemas, resources, collection queries, resource queries, JSON:API routes, include paths, filters, pagination, sortables, multimedia API relationships, API tests, and package service provider wiring."
---

# Vendra Blog API

## Required Context

Always use this skill together with `modular` for module structure, `laravel-best-practices` for Laravel PHP, and `pest-testing` when tests are added or changed.

Before code changes, use Laravel Boost `application-info` and `search-docs` for Laravel, testing, and any API packages involved. Prefer Boost tools for route, database, and error inspection.

## Module Boundary

Treat `app-modules/vendra-blog-api` as the JSON:API layer for `vendra-blog`.

- Use namespace `Misaf\VendraBlogApi`.
- Keep API servers, schemas, API resources, query validators, routes, service providers, and API tests inside this module.
- Import domain models from `Misaf\VendraBlog`; do not duplicate domain models or persistence logic in the API module.
- Respect the domain module's tenant awareness and stay tenant-agnostic: tenancy is inherited from the domain models, which derive it from the bound `TenantResolver` in `misaf/vendra-support`. Add no API tenant toggle and never reference a concrete tenant provider such as `Misaf\VendraTenant` (servers, schemas, queries, routes, or tests). The API must build and run whether or not a tenant provider is installed.
- Keep Filament/admin UI out of this module.
- Keep dependencies explicit in `composer.json`; do not add or change package dependencies without approval.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic. Match the surrounding file's density and do not add comments that restate the code.

## JSON:API Shape

Follow the current `JsonApi/V1` layout.

- Register routes in `routes/api.php` with `JsonApiRoute::server('vendra-blog')->prefix('v1')`.
- Use `JsonApiController` for standard resource endpoints.
- Keep resource type names kebab-case and stable, for example `blog-posts` and `blog-post-categories`.
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
- Expose sortable fields with `->sortable()` and add custom sortables, such as `RandomPositionSort`, in `sortables()`.
- Keep relationship names aligned with the domain model methods: `blogPostCategory`, `blogPosts`, and `multimedia`.

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

- Use `BlogApiServiceProvider` for package configuration and route loading.
- Load only this module's API routes from the API module.
- Keep route middleware consistent with existing routes, including `api` and `vendra.locale`.
- Do not use host-app route files for module endpoints unless integrating the package at application level.

## Testing And Verification

Use Pest tests to protect API contracts.

- Keep tests purposeful and prevent unnecessary ones: cover behavior, contracts, and edge cases — not framework internals or trivially typed code. Do not duplicate coverage a focused test already proves, and do not add throwaway verification scripts (or `tinker`) when a test fits.
- Add route tests for every new resource endpoint and relationship endpoint.
- Add server tests when schemas, resource names, or `Server` behavior changes.
- Add request validation tests when filters, includes, sparse fieldsets, sorting, pagination, or relationship filters change.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets, plus an expectation that the module stays tenant-agnostic, e.g. `arch()->expect('Misaf\VendraBlogApi')->not->toUse('Misaf\VendraTenant')`. The API module may depend on `Misaf\VendraBlog`, but not on any concrete tenant provider.
- Run module checks from the package when possible: `composer --working-dir=app-modules/vendra-blog-api test` and `composer --working-dir=app-modules/vendra-blog-api analyse`.
- If PHP files changed, run Pint for the touched code: `vendor/bin/pint --dirty --format agent` from the host app, or the module formatter if working only inside the package.
