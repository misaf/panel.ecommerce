## Vendra Testing

The `misaf/vendra-testing` package provides shared Pest testing utilities reused across Vendra module test suites.

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep shared testing code inside `packages/vendra-testing` using the `Misaf\VendraTesting` namespace.
- This package owns reusable test helpers such as custom `Expectations` (e.g. sorted translation-key expectations) and `TranslationParity` helpers.
- Keep helpers generic and module-agnostic: no single module's domain models, and no concrete tenant provider (`Misaf\VendraTenant`) references. Utilities must work whether or not a tenant provider is installed.
- Keep expectation and helper signatures stable — they are consumed by many module test suites; changing them ripples widely.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus `arch()->expect('Misaf\VendraTesting')->not->toUse('Misaf\VendraTenant')`.
