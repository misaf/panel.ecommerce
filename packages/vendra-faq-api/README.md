# Vendra FAQ API

Read-only JSON:API resources for Vendra FAQ content.

## Resources

- `GET /v1/faq-categories`
- `GET /v1/faqs`
- Read-only category, FAQ, and multimedia relationships

Requests use Laravel's `api` middleware. Standard JSON:API filtering, sorting, inclusion, and pagination are defined by each resource schema. Applications may optionally resolve the current locale before these routes run.

## Requirements

- PHP 8.3+
- Laravel 13
- `misaf/vendra-api`
- `misaf/vendra-faq`
- `misaf/vendra-multimedia-api`

## Installation

```bash
composer require misaf/vendra-faq-api
```

The service provider, server, and routes are auto-registered.

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
