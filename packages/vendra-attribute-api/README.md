# Vendra Attribute API

Read-only API Platform resources for the `misaf/vendra-attribute` domain module.

## Features

- Dedicated resources for active catalog attributes and options
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

The service provider registers the resources and provider automatically.

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
catalog-option resource class through the provider-neutral Support contract.
Product resources expose stable references without importing this package.

## API Routes

- `GET /api/catalog/attributes`
- `GET /api/catalog/attributes/{id}`
- `GET /api/catalog/attribute-values`
- `GET /api/catalog/attribute-values/{id}`

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
