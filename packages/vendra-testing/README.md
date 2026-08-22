# Vendra Testing

Shared Pest utilities for Vendra package test suites.

## Features

- `toHaveAtLeastTwoLocales()` expectation
- `toHaveTranslationsInSync()` expectation
- `toHaveSortedTranslationKeys()` expectation
- `toSortByEverySortableColumn()` Livewire table expectation
- Deterministic translation file and key parity checks
- Provider-neutral tenant helpers that no-op when tenancy is disabled
- User factory helpers resolved from the configured authentication provider
- Tenant-feature and Filament admin test-context helpers

## Requirements

- PHP 8.4+
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

Use `makeCurrentTestTenantWithFeatures()` when a test needs deterministic
Pennant state, and `setUpFilamentAdminTestContext()` to boot a package's
resources in the shared admin-panel test context.

To exercise every sortable table column in both directions:

```php
expect($listPage)->toSortByEverySortableColumn($recordsInAscendingOrder);
```

## Testing

Run the package checks from the project root:

```bash
php artisan test --compact --testsuite=vendra-testing
composer stan
```

## License

MIT. See [LICENSE](LICENSE).
