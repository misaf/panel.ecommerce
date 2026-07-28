## Vendra API

The `misaf/vendra-api` package provides shared API Platform for Laravel infrastructure — reusable `ApiResource` DTOs such as `ResourceReference`, the generic `EloquentResourceProvider` state provider, and service-provider wiring — consumed by the per-domain API modules (`misaf/vendra-*-api`).

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

### Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

- Keep shared API code inside `packages/vendra-api` using the `Misaf\VendraApi` namespace.
- Use this package for cross-cutting API Platform building blocks: shared `ApiResource` DTOs such as `ResourceReference`, the generic `EloquentResourceProvider` state provider, and API service-provider wiring. Domain resources, state providers, and routes belong in the per-domain API modules, not here.
- Keep every domain API localization-package agnostic: do not require `misaf/vendra-localization` or attach `vendra.locale` inside API packages. Locale-aware behavior may read Laravel's current locale, while the host application decides whether and how to resolve it.
- Depend only on framework and API Platform packages. Never import a domain module or a concrete tenant provider such as `Misaf\VendraTenant`; this module stays domain- and tenant-agnostic.
- Keep shared DTOs and the generic state provider reusable across resource types, and keep their behavior covered by focused tests.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic. Match the surrounding file and do not add comments that restate the code.
- Keep tests purposeful and prevent unnecessary ones: cover behavior, contracts, and edge cases — not framework internals or trivially typed code.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus `arch()->expect('Misaf\VendraApi')->not->toUse('Misaf\VendraTenant')`.
