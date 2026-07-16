## Vendra Cart

The `misaf/vendra-cart` package owns temporary shopping selections before checkout and the Filament administration UI for inspecting carts and their items.

### Standards

- Keep cart domain code inside `packages/vendra-cart` using the `Misaf\VendraCart` namespace.
- `Cart` owns lifecycle data such as its token, optional polymorphic owner, expiration, and items. `CartItem` stores quantity and optional selection metadata.
- Reference products, variants, and other purchasable records only through the `sellable` polymorphic relationship. Never copy catalog ownership, pricing, stock, names, or product-specific classes into this module.
- Keep the package independent of product models, catalog implementations, and concrete tenant providers. Register `CartResource` through `CartPlugin`; sellables from any module resolve through the polymorphic relationship.
- Derive tenant awareness through `misaf/vendra-support`. Apply `BelongsToTenant` to `Cart`; items inherit tenant isolation through their parent cart. Never assign `tenant_id` directly or add a `tenant_aware` config toggle.
- Preserve the unique cart-item identity of `cart_id`, `sellable_type`, and `sellable_id`. Adding the same sellable should change quantity rather than create duplicate rows.
- Treat carts as temporary state. Keep expiration explicit and avoid turning carts into orders, payments, stock reservations, or immutable pricing snapshots; those concerns belong to checkout/order modules.
- Keep the cluster-assigned Filament resource under `src/Filament/Clusters/Resources`, with forms in `Schemas`, tables in `Tables`, and items managed through the cart relation manager. The administration UI may inspect and delete carts/items but must not edit external sellables.
- Display resolved owner records through a human-readable label (`username`, `name`, or `email`) and eager-load the polymorphic owner instead of exposing raw morph type and ID columns.
- Use `CartPolicyEnum` and `CartItemPolicyEnum` as permission sources, keep policies aligned with exposed Filament actions, and update `PermissionPolicySeeder` when abilities change.
- Update English, German, and Persian translation files together and keep their keys in parity.
- Keep `DemoContentSeeder` standalone by resolving existing owners through Laravel's configured authentication model. Fall back to guest carts only when no owners exist, and never fabricate unresolved sellable references.
- Add focused Pest coverage for relationships, migration constraints, policies, Filament registration, configuration, and meaningful user-visible behavior.
- Keep architecture tests enforcing that `Misaf\VendraCart` does not use `Misaf\VendraTenant` or `Misaf\VendraProduct`.
