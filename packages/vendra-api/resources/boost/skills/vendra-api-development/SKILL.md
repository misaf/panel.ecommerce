---
name: vendra-api-development
description: "Create, modify, review, or test the Vendra API infrastructure module in packages/vendra-api. Use for shared API Platform filters, sorters, reusable query building blocks, and API service-provider wiring consumed by the per-domain vendra-*-api modules."
---

# Vendra API

## Workflow

- Inspect `composer.json`, sibling files, and existing tests before changing the package.
- Use Laravel Boost `application-info` and `search-docs` before code changes.
- Apply `laravel-best-practices` to Laravel PHP and `pest-testing` whenever tests change.
- Apply `tailwindcss-development` only when changing Blade markup or Tailwind classes.
- Keep changes inside this package's boundary and preserve its public contracts.
- Add or update focused Pest coverage, then run `composer --working-dir=packages/vendra-api test` and `composer --working-dir=packages/vendra-api analyse`.

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

## Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

## Module Boundary

Treat `packages/vendra-api` as shared API Platform infrastructure, not a domain API.

- Use namespace `Misaf\VendraApi`.
- Keep only cross-cutting, reusable API Platform building blocks here: `JsonApi/Filters`, `JsonApi/Sorting`, and API service-provider wiring.
- Do not add domain schemas, resources, routes, or servers here — those live in the per-domain API modules (`vendra-faq-api`, `vendra-product-api`, `vendra-multimedia-api`, `vendra-blog-api`, `vendra-custom-page-api`).
- Keep all API packages localization-package agnostic: do not require `misaf/vendra-localization` or attach `vendra.locale`; the host application owns optional locale resolution.
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
