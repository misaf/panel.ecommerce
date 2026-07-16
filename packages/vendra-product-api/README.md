# Vendra Product API

Read-only JSON:API resources for the Vendra product catalog.

## Resources

- `GET /v1/product-categories`
- `GET /v1/products`
- `GET /v1/product-prices`
- Read-only category, pricing, multimedia, and optional attribute-value relationships

Requests use the `api` and `vendra.locale` middleware. Standard JSON:API filtering, sorting, inclusion, and pagination are defined by each resource schema.

## Requirements

- PHP 8.3+
- Laravel 13
- `misaf/vendra-api`
- `misaf/vendra-localization`
- `misaf/vendra-multimedia-api`
- `misaf/vendra-product`

## Installation

```bash
composer require misaf/vendra-product-api
```

The service provider, server, and routes are auto-registered.

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
