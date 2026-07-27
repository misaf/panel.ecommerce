# Vendra Testing

Shared Pest utilities for Vendra package test suites.

## Features

- `toHaveAtLeastTwoLocales()` expectation
- `toHaveTranslationsInSync()` expectation
- `toHaveSortedTranslationKeys()` expectation
- Deterministic translation file and key parity checks
- Provider-neutral tenant helpers that no-op when tenancy is disabled
- User factory helpers resolved from the configured authentication provider

## Requirements

- PHP 8.3+
- Laravel 13
- Pest 4
- `misaf/vendra-support`

## Installation

```bash
composer require --dev misaf/vendra-testing
```

Composer loads the custom expectations automatically. Use a package language directory as the expectation value:

```php
expect(__DIR__ . '/../../resources/lang')
    ->toHaveTranslationsInSync('vendra-example')
    ->toHaveSortedTranslationKeys('vendra-example');
```

Tenant-aware package tests should use `makeCurrentTestTenant()`,
`switchToTestTenant()`, and the related helpers instead of importing the
concrete Vendra Tenant provider.

## Testing

Run the package checks from the package directory:

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
