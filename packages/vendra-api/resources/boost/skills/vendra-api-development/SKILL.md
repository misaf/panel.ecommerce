---
name: vendra-api-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra API infrastructure module in packages/vendra-api. Trigger for shared JSON:API filters, sorters, reusable query building blocks, and API service-provider wiring consumed by the per-domain vendra-*-api modules."
---

# Vendra API

## Workflow

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

Always use this skill together with `laravel-best-practices` for Laravel PHP and `pest-testing` when tests are added or changed. Before code changes, use Laravel Boost `application-info` and `search-docs` for Laravel JSON:API.

## Module Boundary

Treat `packages/vendra-api` as shared JSON:API infrastructure, not a domain API.

- Use namespace `Misaf\VendraApi`.
- Keep only cross-cutting, reusable JSON:API building blocks here: `JsonApi/Filters`, `JsonApi/Sorting`, and API service-provider wiring.
- Do not add domain schemas, resources, routes, or servers here — those live in the per-domain API modules (`vendra-faq-api`, `vendra-product-api`, `vendra-multimedia-api`, `vendra-blog-api`).
- Never depend on a domain module or a concrete tenant provider (`Misaf\VendraTenant`); this package must build and run standalone and tenant-agnostic.

## Standards

- Keep filters and sorters generic and resource-type agnostic so any domain API can reuse them.
- Follow Laravel comment style: PHPDoc with array shapes and generics; inline comments only for genuinely complex logic.
- Keep public class and method signatures stable — these are consumed by every domain API module.

## Testing And Verification

- Keep tests purposeful: cover filter/sorter behavior and edge cases, not framework internals.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets, plus `arch()->expect('Misaf\VendraApi')->not->toUse('Misaf\VendraTenant')`.
- Run module checks: `composer --working-dir=packages/vendra-api test` and `composer --working-dir=packages/vendra-api analyse`.
- If PHP files changed, run `vendor/bin/pint --dirty --format agent`.
