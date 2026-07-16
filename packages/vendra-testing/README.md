# Vendra Testing

Shared Pest utilities for Vendra package test suites.

## Features

- `toHaveAtLeastTwoLocales()` expectation
- `toHaveTranslationsInSync()` expectation
- `toHaveSortedTranslationKeys()` expectation
- Deterministic translation file and key parity checks

## Requirements

- PHP 8.3+
- Laravel 13
- Pest 4

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

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
