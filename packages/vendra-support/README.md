# Vendra Support

Shared support infrastructure used by every Vendra module.

## Features

- Provider-neutral tenant resolution and tenant-awareness helpers
- Tenant-aware Eloquent traits, scopes, and migration helpers
- Optional tag, attribute, and currency integration contracts
- Provider-neutral subscription charging with idempotent operation results
- Request and queued-job context propagation primitives
- Shared tenant seeders and console commands
- Shared Filament clusters, navigation taxonomy, and concerns
- Composable policy authorization, sandbox helpers, and shared events

The package binds null capability resolvers by default. Concrete providers can replace those bindings without coupling domain packages to their implementations.

## Requirements

- PHP 8.3+
- Laravel 13
- Filament 5

## Installation

```bash
composer require misaf/vendra-support
```

The service provider is auto-registered.

Optionally publish the shared panel configuration:

```bash
php artisan vendor:publish --tag=vendra-support-config
```

## Testing

Run the package checks from the package directory:

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
