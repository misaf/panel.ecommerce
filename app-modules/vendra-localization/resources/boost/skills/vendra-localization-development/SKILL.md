---
name: vendra-localization-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Localization module in app-modules/vendra-localization. Trigger for the LocaleResolver contract, the resolver chain (Accept-Language, query, route, user), the vendra.locale middleware, Vary-header handling, and localization service-provider wiring."
---

# Vendra Localization

## Required Context

Always use this skill together with `modular` for module structure, `laravel-best-practices` for Laravel PHP, and `pest-testing` when tests are added or changed. Before code changes, use Laravel Boost `application-info` and `search-docs`.

## Module Boundary

Treat `app-modules/vendra-localization` as framework-level request locale resolution.

- Use namespace `Misaf\VendraLocalization`.
- Own the `LocaleResolver` contract, resolver implementations, the resolver chain, the `vendra.locale` middleware, and `Vary`-header support here.
- Keep the module free of domain models and tenant-provider references (`Misaf\VendraTenant`); it must build and run standalone and tenant-agnostic.

## Resolution Standards

- Keep the `ChainLocaleResolver` precedence explicit (e.g. route, then query, then user, then Accept-Language) and documented; each resolver returns a supported locale or nothing.
- Apply the resolved locale via the `vendra.locale` middleware; keep `ProvidesVaryHeaders` accurate so caches vary correctly on negotiated locales.
- Validate resolved locales against the application's supported set before applying them.

## Testing And Verification

- Keep tests purposeful: cover each resolver, the chain precedence, middleware application, and `Vary`-header output.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets, plus `arch()->expect('Misaf\VendraLocalization')->not->toUse('Misaf\VendraTenant')`.
- Run module checks: `composer --working-dir=app-modules/vendra-localization test` and `composer --working-dir=app-modules/vendra-localization analyse`.
- If PHP files changed, run `vendor/bin/pint --dirty --format agent`.
