# Vendra Phone

International user profile phone numbers for Vendra applications.

## Features

- Dynamic `phoneNumbers` relation on Vendra user profiles
- Filament relation manager contributed through the User Profile extension registry
- Canonical E.164 number storage with a separate ISO country state via `ysfkaya/filament-phone-input`
- Open phone type strings and structured JSON metadata for country- or carrier-specific fields
- Tenant-aware storage and permission-seeded authorization

## Requirements

- PHP 8.3+
- Laravel 13
- Filament 5
- `misaf/vendra-user-profile`
- `misaf/vendra-support`
- `ysfkaya/filament-phone-input`

## Installation

```bash
composer require misaf/vendra-phone
php artisan vendor:publish --tag=vendra-phone-migrations
php artisan migrate
```

Tenant columns are determined when the migration runs. If phone numbers must be tenant-scoped, install a tenant provider such as `misaf/vendra-tenant` before running the migration. Enabling tenancy later does not add a tenant column to an existing table.

The service provider and the user-profile relation manager are auto-registered.

## Seeding

Phone number permissions seed automatically when a tenant is provisioned. To seed them manually:

```bash
php artisan vendra-phone:seed {tenant}
```

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
