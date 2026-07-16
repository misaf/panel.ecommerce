# Vendra Address

Country-adaptable user profile addresses for Vendra applications.

## Features

- Dynamic `addresses` relation on Vendra user profiles
- Filament relation manager contributed through the User Profile extension registry
- International address shape: ISO country code, three address lines, locality, administrative area, and sorting code
- Structured JSON metadata for country-specific fields
- Tenant-aware storage and permission-seeded authorization

## Requirements

- PHP 8.3+
- Laravel 13
- Filament 5
- `misaf/vendra-user-profile`
- `misaf/vendra-support`

## Installation

```bash
composer require misaf/vendra-address
php artisan vendor:publish --tag=vendra-address-migrations
php artisan migrate
```

Tenant columns are determined when the migration runs. If addresses must be tenant-scoped, install a tenant provider such as `misaf/vendra-tenant` before running the migration. Enabling tenancy later does not add a tenant column to an existing table.

The service provider and the user-profile relation manager are auto-registered.

## Seeding

Address permissions seed automatically when a tenant is provisioned. To seed them manually:

```bash
php artisan vendra-address:seed {tenant}
```

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
