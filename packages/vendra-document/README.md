# Vendra Document

Private jurisdiction-aware user profile documents for Vendra applications.

## Features

- Dynamic `documents` relation on Vendra user profiles
- Filament relation manager contributed through the User Profile extension registry
- Private single-file document storage through Vendra Multimedia — files are never publicly visible by default
- Open document type with ISO issuing country and structured JSON metadata for jurisdiction-specific fields
- Tenant-aware storage and permission-seeded authorization

## Requirements

- PHP 8.3+
- Laravel 13
- Filament 5
- `misaf/vendra-user-profile`
- `misaf/vendra-multimedia`
- `misaf/vendra-support`

## Installation

```bash
composer require misaf/vendra-document
php artisan vendor:publish --tag=vendra-document-migrations
php artisan migrate
```

Tenant columns are determined when the migration runs. If documents must be tenant-scoped, install a tenant provider such as `misaf/vendra-tenant` before running the migration. Enabling tenancy later does not add a tenant column to an existing table.

The service provider and the user-profile relation manager are auto-registered.

## Seeding

Document permissions seed automatically when a tenant is provisioned. To seed them manually:

```bash
php artisan vendra-document:seed {tenant}
```

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
