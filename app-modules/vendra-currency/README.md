# Vendra Currency

Tenant-aware currency management for Vendra applications.

## Features

- Currency categories
- Currencies with buy/sell pricing and conversion rate
- Filament resources on the `admin` panel

## Requirements

- PHP 8.2+
- Laravel 12
- Filament 5
- Livewire 4
- Pest 4
- Tailwind CSS 4
- `misaf/vendra-tenant`
- `misaf/vendra-activity-log`

## Installation

```bash
composer require misaf/vendra-currency
php artisan vendor:publish --tag=vendra-currency-migrations
php artisan migrate
```

Optional translations publish:

```bash
php artisan vendor:publish --tag=vendra-currency-translations
```

The service provider and Filament plugin are auto-registered.

## Usage

Create a currency category:

```php
use Misaf\VendraCurrency\Models\CurrencyCategory;

$category = CurrencyCategory::query()->create([
    'name' => 'Fiat',
    'description' => 'Government-issued currencies',
    'slug' => 'fiat',
    'position' => 1,
    'status' => true,
]);
```

Create a currency:

```php
use Misaf\VendraCurrency\Models\Currency;

Currency::query()->create([
    'currency_category_id' => $category->id,
    'name' => 'US Dollar',
    'description' => 'United States Dollar',
    'slug' => 'usd',
    'iso_code' => 'USD',
    'conversion_rate' => 1,
    'decimal_place' => 2,
    'buy_price' => 100000,
    'sell_price' => 99500,
    'is_default' => true,
    'position' => 1,
    'status' => true,
]);
```

Load currencies with their category:

```php
$currencies = Currency::query()
    ->with('currencyCategory')
    ->where('status', true)
    ->get();
```

## Filament

Resources are available in the `Currencies` cluster on the `admin` panel:

- Currency Categories
- Currencies

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
