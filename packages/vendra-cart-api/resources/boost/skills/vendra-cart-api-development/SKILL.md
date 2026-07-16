---
name: vendra-cart-api-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Cart JSON:API package in packages/vendra-cart-api. Trigger for cart or cart-item JSON:API schemas, resources, collection/query validation, filters, includes, routes, the vendra-cart JSON:API server, CartApiServiceProvider, cart API serialization, or decisions about exposing polymorphic owners and sellables through JSON:API."
---

# Vendra Cart API

## Workflow

Use this skill with `vendra-api-development`, `laravel-best-practices`, and `pest-testing` when tests change. Before code changes, use Laravel Boost `application-info` and `search-docs` for Laravel JSON:API.

## Boundary

- Keep API code in `packages/vendra-cart-api` with namespace `Misaf\VendraCartApi`.
- Depend on `misaf/vendra-cart` for models and `misaf/vendra-api` for shared JSON:API infrastructure.
- Keep domain behavior, migrations, factories, policies, seeders, and Filament classes out of this package.
- Never reference `Misaf\VendraTenant`; model scopes provide tenant isolation.

## Server And Routes

- Register `Server` as `jsonapi.servers.vendra-cart` with base URI `/v1`.
- Expose `carts` and `cart-items` through the package `routes/api.php` using the `api` and `vendra.locale` middleware.
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
