# Vendra Authify Log

Tenant-aware authentication event logging for Vendra applications.

## Features

- Stores auth events per tenant
- Filament resource and widget on the `admin` panel
- Publishable config, language files, and migrations

## Requirements

- PHP 8.2+
- Laravel 12
- Filament 5
- Livewire 4
- Pest 4
- Tailwind CSS 4
- `misaf/vendra-user`
- `misaf/vendra-tenant`
- `misaf/laravel-authify-log`

## Installation

```bash
composer require misaf/vendra-authify-log
php artisan vendor:publish --tag=vendra-authify-log-migrations
php artisan migrate
```

Optional publishes:

```bash
php artisan vendor:publish --tag=vendra-authify-log-config
php artisan vendor:publish --tag=vendra-authify-log-lang
```

The service provider and Filament plugin are auto-registered.

## Usage

After installation, authentication logs are available from the Filament `admin` panel.

If needed, update configuration in `config/vendra-authify-log.php`.

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
