# Vendra Blog API

Read-only API Platform resources for Vendra blog content.

## Features

- `GET /api/content/blog-post-categories`
- `GET /api/content/blog-posts`
- Read-only category, post, and multimedia relationships

Dedicated DTO resources expose translated content and stable section or asset references. Providers own Eloquent querying, active visibility, filtering, and pagination.

## Requirements

- PHP 8.4+
- Laravel 13
- `misaf/vendra-api`
- `misaf/vendra-blog`
- `misaf/vendra-multimedia-api`

## Installation

```bash
composer require misaf/vendra-blog-api
```

The service provider registers the resources and provider automatically.

## Testing

Run the package checks from the project root:

```bash
php artisan test --compact --testsuite=vendra-blog-api
composer stan
```

## License

MIT. See [LICENSE](LICENSE).
