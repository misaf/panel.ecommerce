# Vendra Blog API

Read-only JSON:API resources for Vendra blog content.

## Features

- `GET /v1/blog-post-categories`
- `GET /v1/blog-posts`
- Read-only category, post, and multimedia relationships

Requests use Laravel's `api` middleware. Standard JSON:API filtering, sorting, inclusion, and pagination are defined by each resource schema. Applications may optionally resolve the current locale before these routes run.

## Requirements

- PHP 8.3+
- Laravel 13
- `misaf/vendra-api`
- `misaf/vendra-blog`
- `misaf/vendra-multimedia-api`

## Installation

```bash
composer require misaf/vendra-blog-api
```

The service provider, server, and routes are auto-registered.

## Testing

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
