---
name: vendra-attribute-development
description: "Create, modify, review, or test the Vendra Attribute package in packages/vendra-attribute. Use for Attribute, AttributeValue, AttributableModel, HasAttributeValues, AttributeResolver wiring, configurable units, optional attribute tags, migrations, factories, policies, Filament resources and relation managers, translations, and package registration."
---

# Vendra Attribute

## Workflow

Use `laravel-best-practices` for Laravel PHP, `pest-testing` when tests change, and `tailwindcss-development` only for Blade or Tailwind UI. Before editing, inspect sibling code and use Laravel Boost `application-info` and `search-docs` for affected Laravel or Filament APIs.

## Domain Boundary

- Work inside `packages/vendra-attribute` with namespace `Misaf\VendraAttribute`.
- Keep attributes consumer-neutral. Add relationships to consumers through `HasAttributeValues`, not hard-coded Product or other domain dependencies.
- Preserve `AttributeValue` as a sortable polymorphic value belonging to one `Attribute` and one `attributable` model.
- Keep `AttributeResolver` bound to the package models through `AttributeServiceProvider`.
- Derive tenancy through `BelongsToTenant` and support-layer schema helpers; never import `Misaf\VendraTenant` or set `tenant_id` manually.
- Use `HasOptionalTags` and `TagIntegration` for optional tags with type `attribute`; do not depend on Vendra Tagger or Spatie Tags.
- Keep unit labels in `vendra-attribute.units` and normalize them through `AttributeUnits::options()`.

## Change Checklist

- Keep resources thin, with form/table definitions and the value relation manager in their existing Filament directories.
- Update migrations, factories, policies, permission seeders, configuration, and translation parity when their contracts change.
- Cover polymorphic values, resolver wiring, units, optional tags, policies, and user-visible Filament behavior with focused Pest tests.
- Preserve architecture expectations against Product, Tenant, Vendra Tagger, and Spatie Tags.
- Run `composer --working-dir=packages/vendra-attribute test` and `composer --working-dir=packages/vendra-attribute analyse`; run Pint when PHP changes.
