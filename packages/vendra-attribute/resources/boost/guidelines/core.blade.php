## Vendra Attribute

The `misaf/vendra-attribute` package owns reusable tenant-aware attributes and polymorphic attribute values, including their Filament administration UI.

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep attribute code inside `packages/vendra-attribute` using the `Misaf\VendraAttribute` namespace.
- Keep the module independent of product or other consumers. Consumers attach values through `HasAttributeValues`; never add consumer-specific columns or relationships to this package.
- `AttributeValue` belongs to an `Attribute` and a polymorphic `attributable`; preserve that generic relationship and the sortable `position` contract.
- Resolve attribute models through the support-layer `AttributeResolver`; keep the provider binding aligned when model contracts change.
- Derive tenancy from `misaf/vendra-support` and let `BelongsToTenant` assign `tenant_id`. Never reference `Misaf\VendraTenant` or set tenant IDs manually.
- Integrate optional tags only through support-layer `HasOptionalTags` / `TagIntegration`, using the reserved `attribute` type. Never import Vendra Tagger or Spatie Tags.
- Keep unit options config-driven through `AttributeUnits`, keep Filament resources thin, and update all supported translations together.
- Because `AttributeResource` declares a `$cluster`, keep its complete resource tree under `src/Filament/Clusters/Resources/`, use the matching `Misaf\VendraAttribute\Filament\Clusters\Resources` namespace, and keep plugin discovery aligned. Any future resource without a cluster must instead live under `src/Filament/Resources/`.
- Keep architecture tests enforcing independence from Product, Tenant, Vendra Tagger, and Spatie Tags.
