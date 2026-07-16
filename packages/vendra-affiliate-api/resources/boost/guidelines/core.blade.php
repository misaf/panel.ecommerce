## Vendra Affiliate API

The `misaf/vendra-affiliate-api` package exposes the public referral lookup surface for `misaf/vendra-affiliate` through Laravel JSON:API.

### Standards

- Keep API code inside `packages/vendra-affiliate-api` using the `Misaf\VendraAffiliateApi` namespace; keep affiliate persistence and workflows in `misaf/vendra-affiliate`.
- Treat the API as public and read-only. Expose active affiliates only, and return only the referral code and creation timestamp; never expose users, commission terms, payouts, or other financial data.
- Keep the active-affiliate constraint in the schema query so collection, filtered, and individual-resource endpoints enforce the same visibility rule.
- Keep the schema, resource, query validators, server registration, and routes synchronized when the JSON:API shape changes.
- Inherit tenant isolation from the Affiliate model and support layer. Never reference `Misaf\VendraTenant` or add an API tenant toggle.
- Cover public field visibility, active/suspended behavior, filters, routes, and server registration with focused Pest tests.
- Keep `tests/ArchTest.php` enforcing the PHP, security, and Laravel presets plus `not->toUse('Misaf\VendraTenant')`.
