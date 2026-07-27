# Vendra Authify Log

Tenant-aware authentication event logging for Vendra applications.

## Features

- Stores auth events per tenant
- Filament resource and widget on the `admin` panel
- Publishable config, language files, and migrations

## Requirements

- PHP 8.3+
- Laravel 13
- Filament 5
- Livewire 4
- Pest 4
- Tailwind CSS 4
- `misaf/vendra-support`
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
php artisan vendor:publish --tag=vendra-authify-log-translations
```

The service provider and Filament plugin are auto-registered.

## Usage

After installation, authentication logs are available from the Filament `admin` panel.

If needed, update configuration in `config/vendra-authify-log.php`.

## Testing

Run the package checks from the package directory:

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
