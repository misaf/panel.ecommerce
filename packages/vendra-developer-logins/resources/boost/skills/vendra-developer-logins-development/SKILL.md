---
name: vendra-developer-logins-development
description: "Create, modify, review, or test the Vendra Developer Logins package in packages/vendra-developer-logins. Use for local-only Filament login shortcuts, DeveloperLoginsRegistrar, role and guard filtering, configured credential or label columns, panel registration, enablement safeguards, package configuration, and registrar tests."
---

# Vendra Developer Logins

## Workflow

Use `laravel-best-practices` for Laravel PHP and `pest-testing` when tests change. Before editing Filament integration, inspect sibling provider code and search the installed documentation with Laravel Boost.

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
