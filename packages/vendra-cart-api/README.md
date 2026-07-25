# Vendra Cart API

Read-only JSON:API resources for tenant-scoped Vendra carts.

## Requirements

- PHP 8.3+
- Laravel 13
- `misaf/vendra-api`
- `misaf/vendra-cart`

## Installation

```bash
composer require misaf/vendra-cart-api
```

The package registers `/v1/carts` and exposes cart items only through the
`/v1/carts/{cart}/items` relationship. Standalone cart-item endpoints are not
registered, ensuring item queries inherit the cart's tenant scope.

## Testing

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
