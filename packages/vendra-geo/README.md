# Vendra Geo

Tenant-aware country, state, and city management for Vendra applications.

## Features

- Country, state, and city models
- Translatable geographic names
- Filament resources for geographic data management
- Tenant-aware seed command integration

## Requirements

- PHP 8.3+
- Laravel 13
- Filament 5
- `misaf/vendra-support`

## Installation

```bash
composer require misaf/vendra-geo
php artisan vendor:publish --tag=vendra-geo-migrations
php artisan migrate
```

The service provider and Filament plugin are auto-registered.

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
