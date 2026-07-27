## Vendra Affiliate API

The `misaf/vendra-affiliate-api` package exposes the public referral lookup surface for `misaf/vendra-affiliate` through Laravel JSON:API.

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

### Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

- Keep API code inside `packages/vendra-affiliate-api` using the `Misaf\VendraAffiliateApi` namespace; keep affiliate persistence and workflows in `misaf/vendra-affiliate`.
- Treat the API as public and read-only. Expose active affiliates only, and return only the referral code and creation timestamp; never expose users, commission terms, payouts, or other financial data.
- Keep the active-affiliate constraint in the schema query so collection, filtered, and individual-resource endpoints enforce the same visibility rule.
- Keep the schema, resource, query validators, server registration, and routes synchronized when the JSON:API shape changes.
- Inherit tenant isolation from the Affiliate model and keep production API code free of `Misaf\VendraTenant` and API tenant toggles. Feature tests may use a concrete tenant factory solely to establish tenant context.
- Cover public field visibility, active/suspended behavior, filters, routes, and server registration with focused Pest tests.
- Keep `tests/ArchTest.php` enforcing the PHP, security, and Laravel presets plus `not->toUse('Misaf\VendraTenant')`.
