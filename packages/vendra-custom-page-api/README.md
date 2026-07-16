# Vendra Custom Page API

Read-only JSON:API resources for Vendra custom pages.

## Resources

- `GET /v1/custom-page-categories`
- `GET /v1/custom-pages`
- Read-only category, page, and multimedia relationships

Requests use the `api` and `vendra.locale` middleware. Standard JSON:API filtering, sorting, inclusion, and pagination are defined by each resource schema.

## Requirements

- PHP 8.3+
- Laravel 13
- `misaf/vendra-api`
- `misaf/vendra-custom-page`
- `misaf/vendra-localization`
- `misaf/vendra-multimedia-api`

## Installation

```bash
composer require misaf/vendra-custom-page-api
```

The service provider, server, and routes are auto-registered.

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
