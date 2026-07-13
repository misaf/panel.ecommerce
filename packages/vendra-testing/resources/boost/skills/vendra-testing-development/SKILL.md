---
name: vendra-testing-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Testing utilities module in packages/vendra-testing. Trigger for shared Pest Expectations, TranslationParity helpers, and other reusable test scaffolding consumed by other Vendra module test suites."
---

# Vendra Testing

## Required Context

Always use this skill together with `modular` for module structure, `laravel-best-practices` for Laravel PHP, and `pest-testing` for test conventions. Before code changes, use Laravel Boost `application-info` and `search-docs`.

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
