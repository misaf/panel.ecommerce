---
name: vendra-attribute-development
description: "Create, modify, review, or test the Vendra Attribute package in packages/vendra-attribute. Use for Attribute, AttributeValue, AttributableModel, HasAttributeValues, AttributeResolver wiring, configurable units, optional attribute tags, migrations, factories, policies, Filament resources and relation managers, translations, and package registration."
---

# Vendra Attribute

## Workflow

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Register every table whose migration calls `TenantSchema::addTenantColumn()` with `TenantTableRegistry` in this package's service provider, preserving configured table names and connections, so `vendra-tenant:enable {tenant}` can retrofit schemas migrated before tenancy was enabled.

Use `laravel-best-practices` for Laravel PHP, `pest-testing` when tests change, and `tailwindcss-development` only for Blade or Tailwind UI. Before editing, inspect sibling code and use Laravel Boost `application-info` and `search-docs` for affected Laravel or Filament APIs.

## Domain Boundary

- Work inside `packages/vendra-attribute` with namespace `Misaf\VendraAttribute`.
- Keep attributes consumer-neutral. Add relationships to consumers through `HasAttributeValues`, not hard-coded Product or other domain dependencies.
- Preserve `AttributeValue` as a sortable polymorphic value belonging to one `Attribute` and one `attributable` model.
- Keep `AttributeResolver` bound to the package models through `AttributeServiceProvider`.
- Derive tenancy through `BelongsToTenant` and support-layer schema helpers; never import `Misaf\VendraTenant` or set `tenant_id` manually.
- Tag-consuming models must use `Misaf\VendraSupport\Traits\HasOptionalTags` as the single source of their `tags()` relationship and pivot metadata. Keep the package tag-agnostic: define a stable package-owned tag type, use `TagIntegration` for availability and UI integration, never import the concrete Vendra Tagger model/provider or define the relationship through Spatie `HasTags`, and list Tagger only under Composer `suggest`.
- Keep unit labels in `vendra-attribute.units` and normalize them through `AttributeUnits::options()`.

## Change Checklist

- Keep every resource that declares a `$cluster`, including its complete supporting tree, under `src/Filament/Clusters/Resources/` with the matching `Misaf\VendraAttribute\Filament\Clusters\Resources` namespace and plugin discovery path. Resources without a cluster belong under `src/Filament/Resources/`.
- Keep `AttributeResource` ungrouped and assign `$navigationSort` from `NavigationPriority::Attributes`; never hardcode numeric resource sort values.
- Provide separate singular and plural resource labels in `en`, `de`, and `fa`: model labels use the singular key, while navigation and plural model labels use the plural key. Keep navigation labels at 24 characters or fewer.
- Keep resources thin, with form/table definitions and the value relation manager in their existing Filament directories.
- Update migrations, factories, policies, permission seeders, configuration, and translation parity when their contracts change.
- Cover polymorphic values, resolver wiring, units, optional tags, policies, and user-visible Filament behavior with focused Pest tests.
- Preserve architecture expectations against Product, Tenant, Vendra Tagger, and Spatie Tags.
- Run `composer --working-dir=packages/vendra-attribute test` and `composer --working-dir=packages/vendra-attribute analyse`; run Pint when PHP changes.
