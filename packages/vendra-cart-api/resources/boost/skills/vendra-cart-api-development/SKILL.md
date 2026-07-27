---
name: vendra-cart-api-development
description: "Create, modify, review, or test the Vendra Cart JSON:API package in packages/vendra-cart-api. Use for cart or cart-item JSON:API schemas, resources, collection/query validation, filters, includes, routes, the vendra-cart JSON:API server, CartApiServiceProvider, cart API serialization, or decisions about exposing polymorphic owners and sellables through JSON:API."
---

# Vendra Cart API

## Workflow

- Inspect `composer.json`, sibling files, and existing tests before changing the package.
- Use Laravel Boost `application-info` and `search-docs` before code changes.
- Apply `laravel-best-practices` to Laravel PHP and `pest-testing` whenever tests change.
- Apply `tailwindcss-development` only when changing Blade markup or Tailwind classes.
- Keep changes inside this package's boundary and preserve its public contracts.
- Add or update focused Pest coverage, then run `composer --working-dir=packages/vendra-cart-api test` and `composer --working-dir=packages/vendra-cart-api analyse`.

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

## Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

Use this skill with `vendra-api-development`, `laravel-best-practices`, and `pest-testing` when tests change. Before code changes, use Laravel Boost `application-info` and `search-docs` for Laravel JSON:API.

## Boundary

- Keep API code in `packages/vendra-cart-api` with namespace `Misaf\VendraCartApi`.
- Depend on `misaf/vendra-cart` for models and `misaf/vendra-api` for shared JSON:API infrastructure.
- Keep domain behavior, migrations, factories, policies, seeders, and Filament classes out of this package.
- Keep production API code free of `Misaf\VendraTenant`; model scopes provide tenant isolation. Feature tests may use a concrete tenant factory solely to establish tenant context.

## Server And Routes

- Register `Server` as `jsonapi.servers.vendra-cart` with base URI `/v1`.
- Expose `carts` and `cart-items` through the package `routes/api.php` using Laravel's `api` middleware without requiring a localization package.
- Keep generic controller routes read-only until authenticated ownership/token access and mutation authorization have an explicit design.
- Register read-only `items` and `cart` relationship endpoints.

## Schemas And Resources

- Serialize cart token, owner label, expiration, timestamps, and items relationship.
- Never serialize raw cart owner morph columns; use `owner_label`.
- Serialize item sellable type/ID, quantity, metadata, timestamps, and cart relationship.
- Keep sellable identity as attributes until all supported sellable resource schemas can be listed on a Laravel JSON:API `MorphTo` field.
- Mark current fields and relationships read-only to match route behavior.
- Support pagination, includes, sparse fieldsets, sorts, counts, and focused validated filters consistently with sibling API modules.

## Verification

- Test route registration, server base URI/schema registration, resource attribute boundaries, and architecture constraints.
- Run `php artisan test --compact packages/vendra-cart-api/tests`.
- Run PHPStan against `packages/vendra-cart-api/src`.
- Run `vendor/bin/pint --dirty --format agent` after PHP changes.
