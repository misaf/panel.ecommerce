# Vendra API

Shared Laravel JSON:API infrastructure for Vendra domain API modules.

## Features

- Reusable relationship filters through `WhereHasInFilter`
- Reusable random ordering through `RandomPositionSort`
- Shared JSON:API package wiring
- Domain- and tenant-provider-agnostic foundations

Domain schemas, resources, servers, and routes belong in packages such as `vendra-product-api`, `vendra-blog-api`, and `vendra-custom-page-api`.

## Requirements

- PHP 8.3+
- Laravel 13
- Laravel JSON:API 5

## Installation

```bash
composer require misaf/vendra-api
```

The service provider is auto-registered.

## Testing

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
