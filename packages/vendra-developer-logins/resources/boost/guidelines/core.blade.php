## Vendra Developer Logins

The `misaf/vendra-developer-logins` package provides local-only login shortcuts for configured Vendra Filament panels.

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep developer-login code inside `packages/vendra-developer-logins` using the `Misaf\VendraDeveloperLogins` namespace.
- Treat local-environment gating as a security boundary. The plugin must stay disabled outside `local`, when configuration disables it, or when no eligible users exist.
- Resolve eligible users through the configured role and authentication guard. Do not broaden the query to arbitrary users or bypass Spatie Permission role scoping.
- Read the credential and label columns from configuration, discard blank or non-string values, and never log or persist credentials.
- Register the plugin only on panels allowed by `ResolvesConfiguredPanels`; preserve host panel overrides.
- Keep the package UI-only and tenant-provider agnostic. Never reference `Misaf\VendraTenant`.
- Cover environment gating, role/guard selection, configured columns, empty datasets, panel registration, and architecture constraints with Pest.
