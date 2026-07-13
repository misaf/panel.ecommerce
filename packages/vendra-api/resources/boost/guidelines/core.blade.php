## Vendra API

The `misaf/vendra-api` package provides shared Laravel JSON:API infrastructure — reusable filters, sorters, and server wiring — consumed by the per-domain API modules (`misaf/vendra-*-api`).

### Standards

- Keep shared API code inside `packages/vendra-api` using the `Misaf\VendraApi` namespace.
- Use this package for cross-cutting JSON:API building blocks: reusable `JsonApi/Filters`, `JsonApi/Sorting`, and API service-provider wiring. Domain schemas, resources, and routes belong in the per-domain API modules, not here.
- Depend only on framework and JSON:API packages. Never import a domain module or a concrete tenant provider such as `Misaf\VendraTenant`; this module stays domain- and tenant-agnostic.
- Keep filters and sorters generic and reusable across resource types, and keep their behavior covered by focused tests.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic. Match the surrounding file and do not add comments that restate the code.
- Keep tests purposeful and prevent unnecessary ones: cover behavior, contracts, and edge cases — not framework internals or trivially typed code.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus `arch()->expect('Misaf\VendraApi')->not->toUse('Misaf\VendraTenant')`.
