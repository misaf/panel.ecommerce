---
name: vendra-affiliate-api-development
description: "Create, modify, review, or test the Vendra Affiliate API package in packages/vendra-affiliate-api. Use for the public affiliates JSON:API server, AffiliateSchema, AffiliateResource, collection/resource queries, active-affiliate visibility, filters, routes, response fields, API tests, and AffiliateApiServiceProvider wiring."
---

# Vendra Affiliate API

## Workflow

Use `vendra-api-development` for shared JSON:API infrastructure, `laravel-best-practices` for Laravel PHP and `pest-testing` when tests change. Before editing, inspect the installed versions with Laravel Boost and search the version-specific documentation for the affected JSON:API behavior.

## Boundary And Contract

- Work inside `packages/vendra-affiliate-api` with namespace `Misaf\VendraAffiliateApi`.
- Reuse `Misaf\VendraAffiliate\Models\Affiliate`; do not duplicate affiliate persistence, status transitions, commissions, referrals, or payouts.
- Preserve the public read-only contract: expose active affiliates only and serialize only `code` and `created_at`.
- Apply active status in `AffiliateSchema::newQuery()` so index, filter, and show endpoints cannot diverge.
- Keep filters intentionally narrow and never expose user relationships or financial fields.
- Inherit tenant scoping from the domain model and keep the production `Misaf\VendraAffiliateApi` namespace free of `Misaf\VendraTenant`. Feature tests may use a concrete tenant factory solely to establish tenant context.

## Change Checklist

- Update the schema, resource, collection/resource queries, server, and routes together when the contract changes.
- Add focused Pest coverage for public fields, active/suspended visibility, filtering, pagination, routes, and server schema registration.
- Preserve the architecture expectation that `Misaf\VendraAffiliateApi` does not use `Misaf\VendraTenant`.
- Run `composer --working-dir=packages/vendra-affiliate-api test` and `composer --working-dir=packages/vendra-affiliate-api analyse`; run Pint when PHP changes.
