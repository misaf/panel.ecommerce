# Vendra User Profile

Tenant-aware user profiles and Filament profile management for Vendra applications.

## Features

- Multiple named profiles per user
- Tenant-aware profile storage and authorization
- Filament resource with create, view, edit, and list pages
- Pennant-controlled module availability

## Requirements

- PHP 8.3+
- Laravel 13
- Filament 5
- Laravel Pennant 1
- `misaf/vendra-user`
- `misaf/vendra-permission`
- `misaf/vendra-support`

## Installation

```bash
composer require misaf/vendra-user-profile
php artisan vendor:publish --tag=vendra-user-profile-migrations
php artisan migrate
```

Tenant columns are determined when the migration runs. If profiles must be tenant-scoped, install a tenant provider such as `misaf/vendra-tenant` before running the migration. Enabling tenancy later does not add a tenant column to an existing table.

The service provider and Filament plugin are auto-registered.

## Feature Flag

The resource is disabled by default. Enable Pennant feature resolution and the global module switch in the published configuration or environment-specific configuration:

```php
'features_enabled' => true,
'module_enabled' => true,
```

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
