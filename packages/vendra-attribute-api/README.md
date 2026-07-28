# Vendra Attribute API

Read-only JSON:API services for the `misaf/vendra-attribute` domain module.

## Features

- Read-only JSON:API resources for active attributes and their values
- Polymorphic attribute values exposed through a consistent API
- Tenant-agnostic — works with or without a tenant provider
- Optional attribute-value relationships on product and product-category endpoints

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

### `attributes` — read-only

Fields: `id`, `name`, `description`, `unit`, `position`, `active`,
`created_at`, and `updated_at`. Only active attributes are returned.

Filters: `id`, `exclude` (by ID).

### `attribute-values` — read-only

Fields: `id`, `attribute_id`, `value`, and read-only `position`. Values whose
attribute is inactive are not returned.

Filters: `id`, `exclude` (by ID).

## Cross-module Integration

When both API modules are installed, `misaf/vendra-product-api` obtains the
attribute-value schema through the provider-neutral Support contract. It adds
`attributeValues` to product categories and adds `attributeValues` plus
`selectedAttributeValues` to products. Neither API package imports the other.

## API Routes

- `GET /v1/attributes` — list active attributes
- `GET /v1/attributes/{id}` — show one active attribute
- `GET /v1/attribute-values` — list attribute values
- `GET /v1/attribute-values/{id}` — show a single attribute value

## Tenant Awareness

Tenancy is inherited from `misaf/vendra-attribute` domain models, which derive tenant behavior from `misaf/vendra-support`. The API module stays tenant-agnostic and never references a concrete tenant provider.

## Testing

Run the package checks from the package directory:

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
