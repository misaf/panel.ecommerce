## Vendra Testing

The `misaf/vendra-testing` package provides shared Pest testing utilities reused across Vendra module test suites.

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep shared testing code inside `packages/vendra-testing` using the `Misaf\VendraTesting` namespace.
- This package owns reusable test helpers such as custom `Expectations` (e.g. sorted translation-key expectations) and `TranslationParity` helpers.
- This package also owns the provider-agnostic test helpers in `src/Helpers.php` (files-autoloaded): `testTenantModel()`, `createTestTenant()`, `makeCurrentTestTenant()`, `switchToTestTenant()`, `currentTestTenant()`, `forgetCurrentTestTenant()` resolve tenancy through the `misaf/vendra-support` contracts (`TenantResolver`, `TenantAwareness`), and `testUserModel()` / `createTestUser()` resolve the user model from `auth.providers.users.model`. They no-op (returning null) when tenancy is disabled.
- **Module test suites must use these helpers instead of importing `Misaf\VendraTenant`.** Only `vendra-tenant` and `vendra-subscription` tests may import the concrete provider; the root `tests/Feature/PackageManifestConsistencyTest.php` guard enforces this.
- Keep helpers generic and module-agnostic: no single module's domain models, and no concrete tenant provider (`Misaf\VendraTenant`) references. `misaf/vendra-support` is the one sanctioned dependency — everything derives from its contracts. Utilities must work whether or not a tenant provider is installed.
- Keep expectation and helper signatures stable — they are consumed by many module test suites; changing them ripples widely.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus `arch()->expect('Misaf\VendraTesting')->not->toUse('Misaf\VendraTenant')`.
