# Vendra Cart

Tenant-aware carts for Vendra applications, with guest UUID tokens, optional
polymorphic owners, polymorphic sellables, quantities, metadata, expiration,
Filament administration, permissions, and demo-content seeding.

## Features

- Guest carts identified by UUID tokens
- Optional polymorphic owners and polymorphic sellable items
- Quantity, metadata, and expiration tracking
- Tenant-aware Filament administration and permission seeding
- Scheduled pruning of expired carts

## Requirements

- PHP 8.3+
- Laravel 13
- Filament 5
- `misaf/vendra-support`

## Installation

```bash
composer require misaf/vendra-cart
php artisan vendor:publish --tag=vendra-cart-migrations
php artisan migrate
```

Optionally publish the configuration and translations:

```bash
php artisan vendor:publish --tag=vendra-cart-config
php artisan vendor:publish --tag=vendra-cart-translations
```

The standalone Filament resource is registered on the configured panels. Cart
creation and mutation remain application concerns; the administration UI is
limited to viewing and deletion.

Expired carts are pruned daily by default. Change the schedule in the
published configuration or run `php artisan vendra-cart:prune-expired`
manually.

## Testing

Run the package checks from the package directory:

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
