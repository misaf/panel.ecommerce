# Vendra Cart API

Authenticated API Platform resources for tenant-scoped Vendra carts.

## Features

- Read-only cart collection and detail endpoints
- Cart items exposed only through their parent cart relationship
- Tenant scope inherited from the parent cart query

## Requirements

- PHP 8.3+
- Laravel 13
- `misaf/vendra-api`
- `misaf/vendra-cart`

## Installation

```bash
composer require misaf/vendra-cart-api
```

The package registers `/api/sales/carts`. Cart lines are embedded DTOs rather
than standalone resources. Sanctum authentication, the resource policy, owner
query constraints, and the domain model's tenant scope protect every operation.

## Testing

Run the package checks from the package directory:

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
