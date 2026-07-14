---
name: vendra-socialite-development
description: "Use this skill when creating, modifying, reviewing, or testing the Vendra Socialite module in packages/vendra-socialite. Trigger for OAuth social login, filament-socialite wiring, the `SocialiteUser` model, `SocialiteRegistrar`, the socialite_users migration, provider (Google/GitHub) configuration, and the socialite service provider / plugin registration."
---

# Vendra Socialite

## Module Boundary

`packages/vendra-socialite` is the optional social-login add-on for Vendra. It depends on `misaf/vendra-user` but `misaf/vendra-user` must never depend on it — requiring this package alone enables social login.

- Use namespace `Misaf\VendraSocialite`.
- Keep the `SocialiteUser` model, `create_socialite_users_table` migration, `Support\SocialiteRegistrar`, config, and `SocialiteServiceProvider` inside this module.
- Do not reintroduce socialite code, config, dependencies, or the `socialite_users` schema into `misaf/vendra-user`.

## Domain Standards

- `SocialiteUser` extends `dutchcodingcompany/filament-socialite`'s base model and adds `BelongsToTenant`, overriding `findForProvider`/`createForProvider` (the base uses `self`, which bypasses the tenant scope).
- Derive tenancy from `misaf/vendra-support` (`BelongsToTenant`, `TenantSchema`), never `Misaf\VendraTenant`.
- Register the `filament-socialite` plugin on the panels in `config/vendra-socialite.php` through `SocialiteServiceProvider`; build the plugin in `SocialiteRegistrar::make()`.
- Map OAuth accounts onto the `misaf/vendra-user` `username`-based schema in `SocialiteRegistrar::createUserUsing` (unique username, verified email, random password); keep tenant assignment to `BelongsToTenant`.
- Provider OAuth credentials belong in the host `config/services.php` (`google`, `github`), not in this module.

## Testing

- Cover the `SocialiteUser` contract/tenant-ownership, `SocialiteRegistrar` username derivation and user creation, and keep the arch presets plus `not->toUse('Misaf\VendraTenant')` in `tests/ArchTest.php`.
- Run module checks from the repo root: `php artisan test --compact --testsuite=vendra-socialite`.
- If PHP files changed, run `vendor/bin/pint --dirty --format agent`.
