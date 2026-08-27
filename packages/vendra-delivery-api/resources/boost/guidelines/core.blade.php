## Vendra Delivery API

The `misaf/vendra-delivery-api` package owns API Platform resources (`ApiResource` DTOs), state providers and processors, and service-provider wiring for `misaf/vendra-delivery`.

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

### Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

- Keep delivery API code inside `packages/vendra-delivery-api` using the `Misaf\VendraDeliveryApi` namespace.
- Keep delivery models, migrations, factories, policies, seeders, zone matching, scheduling rules, and Filament UI in `misaf/vendra-delivery`; this package only serializes and routes them.
- Expose `/delivery/zones`, `/delivery/schedule`, and `/delivery/quotes`. Zones and the schedule are read-only; quoting is a `POST` because a pin is an input, not a lookup key.
- Keep quoting free of writes: it reserves nothing, so a storefront may call it on every drag of the map pin. Throttle it instead.
- Serialize translatable names as locale maps and amounts as integers in minor units alongside their `currencyCode`.
- Never let a client supply a fee. Fees come from the matched band; `requiresQuote` tells the storefront the studio prices that address by hand.
- Restrict collections to active bands and active windows in the links handler, not in the DTO.
- Do the Eloquent querying, hydration, and pagination in the state provider, not the DTO.
- Rely on the delivery models' support-layer tenant scope and keep production API code free of `Misaf\VendraTenant`. Feature tests may use a concrete tenant factory solely to establish tenant context.
- Keep Pest architecture tests and focused resource/state-provider tests current.
