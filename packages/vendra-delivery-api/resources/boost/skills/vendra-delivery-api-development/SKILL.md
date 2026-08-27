---
name: vendra-delivery-api-development
description: "Create, modify, review, or test the Vendra Delivery API module in packages/vendra-delivery-api. Use for DeliveryZoneResource, DeliveryScheduleResource, DeliveryQuoteResource, QuotedDelivery, DeliveryScheduleProvider, QuoteDeliveryProcessor, DeliveryZoneMapper, the /delivery/zones, /delivery/schedule and /delivery/quotes endpoints, delivery MCP tools, or API serialization of delivery zones, windows, dates and fees."
---

# Vendra Delivery API

## Workflow

- Inspect `composer.json`, sibling files, and existing tests before changing the package.
- Use Laravel Boost `application-info` and `search-docs` before code changes.
- Apply `laravel-best-practices` to Laravel PHP and `pest-testing` whenever tests change.
- Keep changes inside this package's boundary and preserve its public contracts.
- Add or update focused Pest coverage, then run `php artisan test --compact --testsuite=vendra-delivery-api` and `composer stan`.

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

Treat `packages/vendra-delivery-api` as the HTTP and MCP surface of `misaf/vendra-delivery`.

- Use namespace `Misaf\VendraDeliveryApi`.
- Keep DTOs in `src/ApiResource`, providers/processors/mappers in `src/State`, and validation rules in `src/Http/Requests`.
- Keep zone matching, scheduling rules, persistence, permissions, and administration in `misaf/vendra-delivery`.
- Keep order placement in `misaf/vendra-order-api`; this package never writes an order or a delivery.
- Keep dependencies explicit and request approval before introducing new packages.

## Domain Standards

- Delegate every decision to `DeliveryZoneMatcher` and `DeliverySchedule`; the API layer maps their answers and nothing else.
- Return `requiresQuote` honestly instead of a zero fee, so the storefront can offer the "ask us" path.
- Serialize amounts as integers in minor units with their `currencyCode`, and translatable names as locale maps.
- Give input DTOs with multi-word properties an explicit `#[SerializedName]`: the configured name converter otherwise maps camelCase wire names onto snake_case PHP properties and silently drops them.
- Use final classes, readonly DTOs, explicit types, PHPDoc generics, and `declare(strict_types=1)`.

## Testing And Verification

- Cover a pin in the free band, a pin in a charged band, a pin beyond every band, inactive bands, the same-day cutoff in the schedule response, and validation of latitude and longitude.
- Run `php artisan test --compact packages/vendra-delivery-api/tests`.
- Run `vendor/bin/pint --dirty --format agent` after changing PHP files.
