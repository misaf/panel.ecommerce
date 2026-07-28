# Vendra API

Shared API Platform infrastructure for Vendra domain API modules.

## Features

- A reusable Eloquent provider with filtering and pagination
- Transport-only resource references for cross-package relationships
- Shared API Platform package wiring
- Domain- and tenant-provider-agnostic foundations

Domain API resources, providers, processors, filters, and operations belong in packages such as `vendra-product-api`, `vendra-blog-api`, and `vendra-custom-page-api`.

Public paths follow `/api/{navigation-group}/{model}`. The group is the stable
slug of the model's Filament cluster (`catalog`, `content`, `marketing`, or
`sales`), while the model segment is the plural kebab-case Eloquent model name.
For example, `Product` is exposed at `/api/catalog/products`.

## Requirements

- PHP 8.3+
- Laravel 13
- API Platform for Laravel 4.3

## Installation

```bash
composer require misaf/vendra-api
```

The service provider is auto-registered.

## Testing

Run the package checks from the package directory:

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
