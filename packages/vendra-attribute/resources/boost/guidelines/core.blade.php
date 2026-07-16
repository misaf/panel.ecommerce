## Vendra Attribute

The `misaf/vendra-attribute` package owns reusable tenant-aware attributes and polymorphic attribute values, including their Filament administration UI.

### Standards

- Keep attribute code inside `packages/vendra-attribute` using the `Misaf\VendraAttribute` namespace.
- Keep the module independent of product or other consumers. Consumers attach values through `HasAttributeValues`; never add consumer-specific columns or relationships to this package.
- `AttributeValue` belongs to an `Attribute` and a polymorphic `attributable`; preserve that generic relationship and the sortable `position` contract.
- Resolve attribute models through the support-layer `AttributeResolver`; keep the provider binding aligned when model contracts change.
- Derive tenancy from `misaf/vendra-support` and let `BelongsToTenant` assign `tenant_id`. Never reference `Misaf\VendraTenant` or set tenant IDs manually.
- Integrate optional tags only through support-layer `HasOptionalTags` / `TagIntegration`, using the reserved `attribute` type. Never import Vendra Tagger or Spatie Tags.
- Keep unit options config-driven through `AttributeUnits`, keep Filament resources thin, and update all supported translations together.
- Keep architecture tests enforcing independence from Product, Tenant, Vendra Tagger, and Spatie Tags.
