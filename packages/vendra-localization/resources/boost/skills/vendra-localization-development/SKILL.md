---
name: vendra-localization-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Localization module in packages/vendra-localization. Trigger for the LocaleResolver contract, the resolver chain (Accept-Language, query, route, user), the vendra.locale middleware, Vary-header handling, and localization service-provider wiring."
---

# Vendra Localization

## Workflow

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

## Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

Always use this skill together with `laravel-best-practices` for Laravel PHP and `pest-testing` when tests are added or changed. Before code changes, use Laravel Boost `application-info` and `search-docs`.

## Module Boundary

Treat `packages/vendra-localization` as framework-level request locale resolution.

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
- Run module checks: `composer --working-dir=packages/vendra-localization test` and `composer --working-dir=packages/vendra-localization analyse`.
- If PHP files changed, run `vendor/bin/pint --dirty --format agent`.
