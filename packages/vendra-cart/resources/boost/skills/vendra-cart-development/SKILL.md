---
name: vendra-cart-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Cart module in packages/vendra-cart. Trigger for Cart, CartItem, temporary shopping selections, polymorphic owners or sellables, cart expiration, cart migrations and factories, cart policies and permission seeders, the vendra-cart:seed command, CartPlugin, CartsCluster, CartResource, cart item relation managers, translations, configuration, or checkout-boundary decisions involving carts."
---

# Vendra Cart

## Workflow

Use this skill with `laravel-best-practices` for Laravel PHP, `pest-testing` when tests change, and `vendra-permission-development` when policies or permissions change. Use `tailwindcss-development` only for Blade or Tailwind UI.

Before code changes, use Laravel Boost `application-info` and `search-docs` for the relevant installed packages. Prefer Boost database and browser tools for application inspection.

## Module Boundary

Treat `packages/vendra-cart` as the source of temporary shopping-selection behavior and its Filament administration UI.

- Use namespace `Misaf\VendraCart`.
- Keep models, factories, migrations, policies, permission enums, seed commands, Filament classes, config, translations, and tests inside this module.
- Keep checkout, orders, payment, inventory reservation, and catalog ownership outside this module.
- Keep API schemas and routes in a dedicated API module if introduced later.
- Keep dependencies explicit and request approval before introducing new packages.

## Domain Standards

- Model a cart with an opaque UUID token, optional polymorphic `owner`, optional `expires_at`, and `items()` relationship.
- Model each item with a parent cart, polymorphic `sellable`, positive quantity, and optional JSON metadata for selection-specific values.
- Reference catalog records; never duplicate or own product/variant names, descriptions, prices, stock, or lifecycle data.
- Keep the package independent of concrete catalog implementations. Register its administration resource standalone; products, variants, and other sellables resolve through `MorphTo`.
- Preserve the unique database key on `cart_id`, `sellable_type`, and `sellable_id`. Merge quantity for an existing sellable instead of creating a duplicate item.
- Store pricing snapshots only when a checkout/order requirement explicitly introduces that boundary. Do not silently turn temporary cart rows into financial records.
- Use typed Eloquent relationships with PHPDoc generics, Laravel model attributes, explicit casts, final classes, and `declare(strict_types=1)`.

## Tenant Awareness

- Derive tenancy only from `misaf/vendra-support`; never reference `Misaf\VendraTenant`.
- Apply `BelongsToTenant` to `Cart` and use `TenantSchema` in its migration.
- Do not apply a second tenant scope to `CartItem`; tenant isolation flows through the required cart relationship.
- Never assign `tenant_id` manually or add a `tenant_aware` configuration value.
- Keep factories, permission seeders, and `DemoContentSeeder` functional with tenancy enabled or disabled.

## Filament And Permissions

- Register the standalone `CartResource` through `CartPlugin` and `CartServiceProvider`, respecting configured panel IDs.
- Keep standalone Filament resources under `src/Filament/Resources`; delegate schemas and tables to dedicated classes.
- Keep the administration surface operational and catalog-safe: allow authorized users to list, view, and delete carts or items, but do not edit external sellable data.
- Display the resolved owner using `username`, `name`, or `email` with a route-key fallback. Eager-load the polymorphic owner in tables and do not expose raw morph type/ID as the primary label.
- Use Filament v5 namespaces: fields from `Filament\Forms\Components`, layout from `Filament\Schemas\Components`, columns from `Filament\Tables\Columns`, actions from `Filament\Actions`, and icons from `Filament\Support\Icons\Heroicon`.
- Use `vendra-cart::attributes` and `vendra-cart::navigation` translation keys for every visible label.
- Keep policy methods aligned with exposed actions and backed by `CartPolicyEnum` or `CartItemPolicyEnum` permissions.
- Update `PermissionPolicySeeder` and `vendra-cart:seed` whenever permission cases change.

## Data And Localization

- Keep the package migration reversible and ensure deleting a cart cascades to its items without affecting sellables.
- Keep UUID cart tokens unique and expiration indexed within the tenant boundary.
- Update English, German, and Persian locale files together, with sorted keys and parity.
- Keep metadata optional and selection-focused; avoid using it as an unstructured copy of catalog data.
- Resolve demo cart owners through Laravel's configured authentication model and rely on its tenant scope. Fall back to guest carts only when no owners exist; do not fabricate polymorphic sellables that may not exist in the host application.

## Testing And Verification

- Add focused Pest tests for polymorphic relationships, defaults, migration constraints, policy permissions, plugin/resource registration, and tenant independence.
- Add Livewire tests when Filament interaction behavior changes materially.
- Keep `tests/ArchTest.php` enforcing no dependency on `Misaf\VendraTenant` or `Misaf\VendraProduct`.
- Run `php artisan test --compact packages/vendra-cart/tests`.
- Run PHPStan against the module source, factories, and seeders.
- Run `vendor/bin/pint --dirty --format agent` after changing PHP files.
