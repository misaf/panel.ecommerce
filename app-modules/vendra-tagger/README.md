# Vendra Tagger

Tenant-aware tag management for Vendra applications.

## Features

- Manages tags in the `tags` table with tenant support
- Renames Spatie `order_column` to `position`
- Filament resource on the `admin` panel

## Requirements

- PHP 8.2+
- Laravel 12
- Filament 5
- Livewire 4
- Pest 4
- Tailwind CSS 4
- `filament/spatie-laravel-tags-plugin`
- `misaf/vendra-tenant`
- `misaf/vendra-user`

## Installation

```bash
composer require misaf/vendra-tagger
php artisan vendor:publish --tag=tags-migrations
php artisan migrate
php artisan vendor:publish --tag=vendra-tagger-migrations
php artisan migrate
```

Optional translations publish:

```bash
php artisan vendor:publish --tag=vendra-tagger-translations
```

The service provider and Filament plugin are auto-registered.

## Usage

Create a tag:

```php
use Misaf\VendraTagger\Models\Tagger;

$tag = Tagger::query()->create([
    'name' => ['en' => 'Featured'],
    'slug' => ['en' => 'featured'],
    'type' => 'content',
    'position' => 1,
]);
```

Load tags by type:

```php
$tags = Tagger::query()
    ->where('type', 'content')
    ->orderBy('position')
    ->get();
```

## Filament

Tag management is available from the `admin` panel under content management.

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
