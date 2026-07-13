# Vendra Activity Log

Tenant-aware activity logging for Vendra applications.

## Features

- Tenant-scoped activity logs
- Filament resource and widget on the `admin` panel
- Translation and migration publishing support
- Tenant-aware permission policy seeding

## Requirements

- PHP 8.3+
- Laravel 13
- Filament 5
- Livewire 4
- Pest 4
- Tailwind CSS 4
- `awcodes/filament-badgeable-column`
- `misaf/vendra-tenant`
- `mokhosh/filament-jalali`
- `spatie/laravel-activitylog`

## Installation

```bash
composer require misaf/vendra-activity-log
php artisan vendor:publish --tag=activitylog-migrations
php artisan vendor:publish --tag=vendra-activity-log-migrations
php artisan migrate
```

Set the activity model in `config/activitylog.php`:

```php
'activity_model' => \Misaf\VendraActivityLog\Models\ActivityLog::class,
```

Optional translations publish:

```bash
php artisan vendor:publish --tag=vendra-activity-log-translations
```

## Permissions

Seed activity log permissions for a tenant by ID or slug:

```bash
php artisan vendra-activity-log:seed {tenant} permissions
```

To run every activity log seeder:

```bash
php artisan vendra-activity-log:seed {tenant} all
```

## Usage

Use Spatie activity logging as usual:

```php
activity()
    ->causedBy(auth()->user())
    ->performedOn($model)
    ->withProperties(['key' => 'value'])
    ->log('Did something');
```

In Filament, logs are available on the `admin` panel.

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
