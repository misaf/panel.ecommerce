# Vendra Cart

Tenant-aware carts for Vendra applications, with guest UUID tokens, optional
polymorphic owners, polymorphic sellables, quantities, metadata, expiration,
Filament administration, permissions, and demo-content seeding.

## Installation

```bash
composer require misaf/vendra-cart
php artisan vendor:publish --tag=vendra-cart-migrations
php artisan migrate
```

The standalone Filament resource is registered on the configured panels. Cart
creation and mutation remain application concerns; the administration UI is
limited to viewing and deletion.

## Testing

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
