# Vendra FAQ API

Read-only API Platform resources for Vendra help content.

## Features

- `GET /api/content/faq-categories`
- `GET /api/content/faqs`
- Read-only category, FAQ, and multimedia relationships

Dedicated DTO resources expose translated content and stable topic or asset references. Providers own Eloquent querying, active visibility, and pagination.

## Requirements

- PHP 8.4+
- Laravel 13
- `misaf/vendra-api`
- `misaf/vendra-faq`
- `misaf/vendra-multimedia-api`

## Installation

```bash
composer require misaf/vendra-faq-api
```

The service provider registers the resources and provider automatically.

## Testing

Run the package checks from the project root:

```bash
php artisan test --compact --testsuite=vendra-faq-api
composer stan
```

## License

MIT. See [LICENSE](LICENSE).
