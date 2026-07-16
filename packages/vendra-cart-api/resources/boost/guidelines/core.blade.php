## Vendra Cart API

The `misaf/vendra-cart-api` package owns JSON:API schemas, resources, query validation, routes, and server wiring for `misaf/vendra-cart`.

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep cart API code inside `packages/vendra-cart-api` using the `Misaf\VendraCartApi` namespace.
- Keep cart models, migrations, factories, policies, seeders, and Filament UI in `misaf/vendra-cart`; this package only serializes and routes them.
- Register `carts` and `cart-items` on the `vendra-cart` JSON:API server under `/v1`.
- Keep endpoints read-only until authenticated cart ownership, token access, and mutation authorization are explicitly designed and tested. Never expose unauthenticated cart mutation through the generic JSON:API controller.
- Serialize the cart's human-readable `owner_label`; do not expose raw `owner_type` or `owner_id` attributes.
- Serialize `sellable_type` and `sellable_id` as identity attributes until every supported sellable type has a registered JSON:API schema. Do not claim a polymorphic JSON:API relationship with incomplete inverse types.
- Keep cart/item relationships read-only and support includes for `items` and `cart`.
- Use focused collection filters for IDs, cart token, expiration, quantity, sellable identity, and relationship presence.
- Rely on the cart models' support-layer tenant scope and keep production API code free of `Misaf\VendraTenant`. Feature tests may use a concrete tenant factory solely to establish tenant context.
- Keep Pest architecture tests and focused route/server/resource tests current.
