---
name: vendra-attribute-api-development
description: "Create, modify, review, or test the Vendra Attribute API package in packages/vendra-attribute-api. Use for the v1 JSON:API server, AttributeValueSchema, AttributeValueResource, filters, routes, response fields, read-only behavior, API tests, and AttributeApiServiceProvider wiring."
---

# Vendra Attribute API

## Workflow

Use `vendra-api-development` for shared JSON:API infrastructure, `laravel-best-practices` for Laravel PHP and `pest-testing` when tests change. Before editing, use Laravel Boost to inspect installed versions and search documentation for affected JSON:API behavior.

## Boundary And Contract

- Work inside `packages/vendra-attribute-api` with namespace `Misaf\VendraAttributeApi`.
- Reuse `Misaf\VendraAttribute\Models\AttributeValue`; do not duplicate attribute persistence or polymorphic relationships.
- Preserve the v1 shape: `attribute_id`, `value`, and read-only `position`. Keep raw `attributable_type` and `attributable_id` private unless a complete relationship contract is designed.
- Keep the API read-only while the server is non-authorizable; do not add generic mutations.
- Inherit tenant scoping from the domain model and keep the production `Misaf\VendraAttributeApi` namespace free of `Misaf\VendraTenant`. Feature tests may use a concrete tenant factory solely to establish tenant context.

## Change Checklist

- Update schema fields, resource serialization, server registration, routes, and tests together.
- Add focused Pest coverage for routes, fields, filters, pagination, read-only behavior, and server schema registration.
- Preserve the architecture expectation that `Misaf\VendraAttributeApi` does not use `Misaf\VendraTenant`.
- Run `composer --working-dir=packages/vendra-attribute-api test` and `composer --working-dir=packages/vendra-attribute-api analyse`; run Pint when PHP changes.
