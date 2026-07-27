# Vendra Multimedia API

Read-only JSON:API resources for Vendra media records.

## Features

- `GET /v1/multimedia`
- Individual media record retrieval
- Schema-defined filtering, sorting, and pagination

Requests use Laravel's `api` middleware. Applications may optionally resolve the current locale before these routes run. Other domain API modules reuse this package for multimedia relationships.

## Requirements

- PHP 8.3+
- Laravel 13
- `misaf/vendra-api`
- `misaf/vendra-multimedia`

## Installation

```bash
composer require misaf/vendra-multimedia-api
```

The service provider, server, and routes are auto-registered.

## Testing

Run the package checks from the package directory:

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
