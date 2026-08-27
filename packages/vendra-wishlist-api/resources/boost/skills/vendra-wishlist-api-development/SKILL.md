---
name: vendra-wishlist-api-development
description: "Create, modify, review, or test the Vendra Wishlist API module in packages/vendra-wishlist-api. Use for WishlistResource, SavedItemResource, SavedItem, SaveWishlistItemProcessor, ForgetWishlistItemProcessor, WishlistMapper, WishlistLinksHandler, CustomerWishlistPolicy, the /customers/wishlists and /customers/saved-items endpoints, wishlist MCP tools, or API serialization of saved selections."
---

# Vendra Wishlist API

## Workflow

- Inspect `composer.json`, sibling files, and existing tests before changing the package.
- Use Laravel Boost `application-info` and `search-docs` before code changes.
- Apply `laravel-best-practices` to Laravel PHP and `pest-testing` whenever tests change.
- Keep changes inside this package's boundary and preserve its public contracts.
- Add or update focused Pest coverage, then run `php artisan test --compact --testsuite=vendra-wishlist-api` and `composer stan`.

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

## Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

Use this skill with `laravel-best-practices` for Laravel PHP and `pest-testing` when tests change.

## Module Boundary

Treat `packages/vendra-wishlist-api` as the HTTP and MCP surface of `misaf/vendra-wishlist`.

- Use namespace `Misaf\VendraWishlistApi`.
- Keep DTOs in `src/ApiResource`, providers/processors/mappers in `src/State`, validation rules in `src/Http/Requests`, and authorization in `src/Policies`.
- Keep wishlist persistence, idempotent saving, permissions, and administration in `misaf/vendra-wishlist`.
- Keep catalog ownership in `misaf/vendra-product`; read from it, never write to it.
- Keep carts, orders, and checkout out entirely.
- Keep dependencies explicit and request approval before introducing new packages.

## Domain Standards

- Delegate writes to `AddWishlistItemAction`; the API layer resolves the list, checks the catalog, and maps the answer.
- Keep saving idempotent from the caller's point of view: a second save answers the same list, not an error.
- Scope every read and every delete to the authenticated owner.
- Serialize `savedAt` as an ISO-8601 timestamp and keep item metadata opaque.
- Use final classes, readonly DTOs, explicit types, PHPDoc generics, and `declare(strict_types=1)`.

## Testing And Verification

- Cover ownership scoping, unauthenticated access, saving, saving twice, an unknown sellable, forgetting, and forgetting somebody else's item.
- Run `php artisan test --compact packages/vendra-wishlist-api/tests`.
- Run `vendor/bin/pint --dirty --format agent` after changing PHP files.
