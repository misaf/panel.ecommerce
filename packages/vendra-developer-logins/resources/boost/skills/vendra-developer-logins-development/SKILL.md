---
name: vendra-developer-logins-development
description: "Create, modify, review, or test the Vendra Developer Logins package in packages/vendra-developer-logins. Use for local-only Filament login shortcuts, DeveloperLoginsRegistrar, role and guard filtering, configured credential or label columns, panel registration, enablement safeguards, package configuration, and registrar tests."
---

# Vendra Developer Logins

## Workflow

- Inspect `composer.json`, sibling files, and existing tests before changing the package.
- Use Laravel Boost `application-info` and `search-docs` before code changes.
- Apply `laravel-best-practices` to Laravel PHP and `pest-testing` whenever tests change.
- Apply `tailwindcss-development` only when changing Blade markup or Tailwind classes.
- Keep changes inside this package's boundary and preserve its public contracts.
- Add or update focused Pest coverage, then run `composer --working-dir=packages/vendra-developer-logins test` and `composer --working-dir=packages/vendra-developer-logins analyse`.

## Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

## Vendra Transitive API Policy

- Treat a Vendra dependency intentionally exposed through the public API of a directly required Vendra platform package as part of the supported public contract of that package.
- Do not add a redundant direct Composer requirement solely because source code imports a type from that exposed dependency.
- Apply this only to Vendra platform packages listed under `require`; never extend it to `require-dev`, `suggest`, incidental implementation dependencies, or third-party packages. Removing or replacing an exposed dependency is a breaking change; keep `self.version` alignment across the Vendra package graph.

## Security Boundary

- Work inside `packages/vendra-developer-logins` with namespace `Misaf\VendraDeveloperLogins`.
- Preserve all enablement gates: local environment, enabled configuration, and at least one eligible user.
- Resolve users through the configured role and current authentication guard; do not expose arbitrary accounts.
- Respect configured credential and label columns, discard invalid values, and never log credentials.
- Register through `DeveloperLoginsServiceProvider` only on configured panels.
- Keep the package tenant-provider agnostic and never import `Misaf\VendraTenant`.

## Change Checklist

- Add focused Pest tests for local/non-local behavior, disabled configuration, missing roles/users, role and guard filtering, configured columns, switching, and panel registration.
- Preserve the architecture expectation that `Misaf\VendraDeveloperLogins` does not use `Misaf\VendraTenant`.
- Run `composer --working-dir=packages/vendra-developer-logins test` and `composer --working-dir=packages/vendra-developer-logins analyse`; run Pint when PHP changes.
