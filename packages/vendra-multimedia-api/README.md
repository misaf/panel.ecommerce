# Vendra Multimedia API

Read-only API Platform resources for Vendra media records.

## Features

- `GET /api/content/multimedia`
- Individual media record retrieval
- MIME type filtering and pagination

The asset DTO intentionally omits disks, paths, and other storage internals.
Other API modules use stable resource references and do not depend on this package.

## Requirements

- PHP 8.4+
- Laravel 13
- `misaf/vendra-api`
- `misaf/vendra-multimedia`

## Installation

```bash
composer require misaf/vendra-multimedia-api
```

The service provider registers the resource and provider automatically.

## Testing

Run the package checks from the project root:

```bash
php artisan test --compact --testsuite=vendra-multimedia-api
composer stan
```

## License

MIT. See [LICENSE](LICENSE).
