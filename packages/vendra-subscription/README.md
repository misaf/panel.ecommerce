# Vendra Subscription

Tenant provisioning orchestration for Vendra applications.

## Features

- Creates a tenant and its primary domain
- Creates the initial administrator and super-admin role
- Optionally runs registered tenant seeders
- Dispatches the shared tenant-provisioned event
- Supports idempotent provisioning with `--if-missing`

This package currently owns tenant provisioning only. It does not provide plan, billing, or recurring subscription models.

## Requirements

- PHP 8.3+
- Laravel 13
- `misaf/vendra-tenant`
- `misaf/vendra-user`
- `misaf/vendra-permission`
- `misaf/vendra-support`

## Installation

```bash
composer require misaf/vendra-subscription
```

Provision a tenant interactively:

```bash
php artisan vendra-subscription:provision
```

For repeatable provisioning, use `--if-missing` to return successfully without creating another tenant when the domain already exists:

```bash
php artisan vendra-subscription:provision --if-missing
```

Use `php artisan vendra-subscription:provision --help` for the available arguments and options.

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
