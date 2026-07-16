---
name: vendra-testing-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Testing utilities module in packages/vendra-testing. Trigger for shared Pest Expectations, TranslationParity helpers, and other reusable test scaffolding consumed by other Vendra module test suites."
---

# Vendra Testing

## Workflow

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

Always use this skill together with `laravel-best-practices` for Laravel PHP and `pest-testing` for test conventions. Before code changes, use Laravel Boost `application-info` and `search-docs`.

## Module Boundary

Treat `packages/vendra-testing` as shared, module-agnostic test utilities.

- Use namespace `Misaf\VendraTesting`.
- Own reusable Pest expectations and helpers (`Expectations`, `TranslationParity`) here.
- Keep utilities generic: no dependency on a specific domain module and no concrete tenant provider reference (`Misaf\VendraTenant`).
- Keep cross-module dependencies explicit in `composer.json`.

## Utility Standards

- Keep custom expectations composable and well-named so consuming suites read clearly.
- Keep translation-parity and key-sorting helpers deterministic and locale-order independent.
- Treat helper signatures as a public contract; deprecate rather than break when evolving them.

## Testing And Verification

- Cover the utilities themselves with focused tests where behavior is non-trivial (e.g. key-sorting comparison logic).
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets, plus `arch()->expect('Misaf\VendraTesting')->not->toUse('Misaf\VendraTenant')`.
- Run module checks: `composer --working-dir=packages/vendra-testing test` and `composer --working-dir=packages/vendra-testing analyse`.
- If PHP files changed, run `vendor/bin/pint --dirty --format agent`.
