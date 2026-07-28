# Vendra Product API

Read-only API Platform resources for the Vendra product catalog.

## Features

- `GET /api/catalog/product-categories`
- `GET /api/catalog/products`
- `GET /api/catalog/product-prices`
- Read-only category, pricing, multimedia, and optional attribute-value relationships

Dedicated DTO resources expose translated catalog data and stable references for groups, prices, multimedia, and optional attribute options. Providers own Eloquent querying, filters, and pagination.

## Requirements

- PHP 8.3+
- Laravel 13
- `misaf/vendra-api`
- `misaf/vendra-multimedia-api`
- `misaf/vendra-product`
- `misaf/vendra-support`

## Installation

```bash
composer require misaf/vendra-product-api
```

The service provider registers the resources and provider automatically.

## Testing

Run the package checks from the package directory:

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
