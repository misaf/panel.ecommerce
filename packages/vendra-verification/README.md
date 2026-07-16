# Vendra Verification

Provider-neutral user profile verification records for Vendra applications.

## Features

- Dynamic `verifications` relation on Vendra user profiles
- Filament relation manager contributed through the User Profile extension registry
- Open verification types, statuses, and providers — no vendor lock-in
- ISO country code and structured JSON metadata for jurisdiction- or provider-specific fields
- Tenant-aware storage and permission-seeded authorization

## Requirements

- PHP 8.3+
- Laravel 13
- Filament 5
- `misaf/vendra-user-profile`
- `misaf/vendra-support`

## Installation

```bash
composer require misaf/vendra-verification
php artisan vendor:publish --tag=vendra-verification-migrations
php artisan migrate
```

Tenant columns are determined when the migration runs. If verifications must be tenant-scoped, install a tenant provider such as `misaf/vendra-tenant` before running the migration. Enabling tenancy later does not add a tenant column to an existing table.

The service provider and the user-profile relation manager are auto-registered.

## Seeding

Verification permissions seed automatically when a tenant is provisioned. To seed them manually:

```bash
php artisan vendra-verification:seed {tenant}
```

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
