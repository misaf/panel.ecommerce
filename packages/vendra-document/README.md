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

Tenant columns are added automatically when a tenant provider is active. If
tenancy is enabled after this migration has run, use
`php artisan vendra-tenant:enable {tenant}` to retrofit the table and assign
existing unscoped records.

The service provider and the user-profile relation manager are auto-registered.

## Seeding

Document permissions seed automatically when a tenant is provisioned. To seed them manually:

```bash
php artisan vendra-document:seed {tenant}
```

## Testing

Run the package checks from the package directory:

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
