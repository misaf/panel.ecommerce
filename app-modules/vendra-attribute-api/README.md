# Vendra Attribute API

JSON:API services for the `misaf/vendra-attribute` domain module. Exposes attribute values through a Laravel JSON:API interface.

## Features

- Read-only JSON:API resource for `AttributeValue` models
- Polymorphic attribute values exposed through a consistent API
- Tenant-agnostic — works with or without a tenant provider
- Conditionally integrated into `misaf/vendra-product-api` when both packages are installed (attribute values appear on product endpoints automatically)

## Requirements

- PHP 8.3+
- Laravel 13
- `misaf/vendra-attribute`
- `misaf/vendra-api`

## Installation

```bash
composer require misaf/vendra-attribute-api
```

The service provider is auto-registered. The JSON:API server registers at `/v1`.

## Resources

### `attribute-values` — read-only

| Field          | Type   | Notes            |
|----------------|--------|------------------|
| `id`           | ID     |                  |
| `attribute_id` | Number |                  |
| `value`        | Str    |                  |
| `position`     | Number | Read-only        |
| `created_at`   | Carbon | Read-only        |
| `updated_at`   | Carbon | Read-only        |

Filters: `id`, `exclude` (by ID).

## Cross-module Integration

When `misaf/vendra-product-api` detects this package, it conditionally registers `attributeValues` as a relationship on the `products` resource and serves `AttributeValueSchema` under the `vendra-product` server. No configuration required.

## API Routes

- `GET /v1/attribute-values` — list attribute values
- `GET /v1/attribute-values/{id}` — show a single attribute value

## Tenant Awareness

Tenancy is inherited from `misaf/vendra-attribute` domain models, which derive tenant behavior from `misaf/vendra-support`. The API module stays tenant-agnostic and never references a concrete tenant provider.

## Testing and Analysis

```bash
composer test
composer analyse
```

## License

MIT.
