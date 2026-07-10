## Vendra Testing

The `misaf/vendra-testing` package provides shared Pest testing utilities reused across Vendra module test suites.

### Standards

- Keep shared testing code inside `app-modules/vendra-testing` using the `Misaf\VendraTesting` namespace.
- This package owns reusable test helpers such as custom `Expectations` (e.g. sorted translation-key expectations) and `TranslationParity` helpers.
- Keep helpers generic and module-agnostic: no single module's domain models, and no concrete tenant provider (`Misaf\VendraTenant`) references. Utilities must work whether or not a tenant provider is installed.
- Keep expectation and helper signatures stable — they are consumed by many module test suites; changing them ripples widely.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus `arch()->expect('Misaf\VendraTesting')->not->toUse('Misaf\VendraTenant')`.
