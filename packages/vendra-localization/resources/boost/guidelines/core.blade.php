## Vendra Localization

The `misaf/vendra-localization` package resolves the request locale for Laravel applications through a configurable chain of resolvers and locale middleware.

### Standards

### Translatable Persistence

- Making a persisted model field translatable is an explicit domain choice unless this package already requires it.
- Every field listed in a model's `$translatable` array must definitely use a JSON database column. Keep its model traits/casts, factories, validation, Filament locale UI, API serialization, and tests translation-aware.
- A field not listed in `$translatable` must use the appropriate scalar database type and must not use Spatie Translatable, translatable slug traits, locale switchers, translated callbacks, or translation-shaped array data.

- Keep localization code inside `packages/vendra-localization` using the `Misaf\VendraLocalization` namespace.
- This package owns the `Contracts\LocaleResolver`, the resolver chain (`ChainLocaleResolver`, `AcceptLanguageLocaleResolver`, `QueryLocaleResolver`, `RouteLocaleResolver`, `UserLocaleResolver`), the `vendra.locale` middleware, `ProvidesVaryHeaders`, and `LocalizationServiceProvider`.
- Keep locale resolution generic and framework-level; do not embed domain-model assumptions. It has no domain models.
- Keep the module tenant-agnostic: never reference a concrete tenant provider such as `Misaf\VendraTenant`.
- Keep the resolver chain order and the `Vary` header behavior intentional and covered by tests; document precedence when adding a resolver.
- Follow Laravel comment style: document with PHPDoc (array shapes, generics, `@see`) and reserve inline comments for genuinely complex logic.
- Keep Pest architecture tests in `tests/ArchTest.php`: the `php`, `security`, and `laravel` presets plus `arch()->expect('Misaf\VendraLocalization')->not->toUse('Misaf\VendraTenant')`.
