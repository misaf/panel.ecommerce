# Vendra Custom Page API

Read-only API Platform resources for Vendra custom pages.

## Features

- `GET /api/content/custom-page-categories`
- `GET /api/content/custom-pages`
- Read-only category, page, and multimedia relationships

Dedicated DTO resources expose translated content and stable section or asset references. Providers own Eloquent querying, active visibility, filtering, and pagination.

## Requirements

- PHP 8.4+
- Laravel 13
- `misaf/vendra-api`
- `misaf/vendra-custom-page`
- `misaf/vendra-multimedia-api`

## Installation

```bash
composer require misaf/vendra-custom-page-api
```

The service provider registers the resources and provider automatically.

## Testing

Run the package checks from the project root:

```bash
php artisan test --compact --testsuite=vendra-custom-page-api
composer stan
```

## License

MIT. See [LICENSE](LICENSE).
