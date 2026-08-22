# Vendra Currency

Tenant-aware currency management for Vendra applications.

## Features

- Catalog of installable currencies: ISO 4217 fiat (via `moneyphp/money`) and crypto currencies (via `moneyphp/crypto-currencies`)
- Enable/disable installed currencies per tenant with a single enforced default currency
- Money formatting through `cknow/laravel-money`
- Filament resource on the `admin` panel

## Requirements

- PHP 8.4+
- Laravel 13
- Filament 5
- Livewire 4
- Pest 4
- `misaf/vendra-support`

## Installation

```bash
composer require misaf/vendra-currency
php artisan vendor:publish --tag=vendra-currency-migrations
php artisan migrate
```

Optional configuration and translations:

```bash
php artisan vendor:publish --tag=vendra-currency-config
php artisan vendor:publish --tag=vendra-currency-translations
```

The service provider and Filament plugin are auto-registered.

## Usage

Install a currency from the catalog:

```php
use Misaf\VendraCurrency\Models\Currency;
use Misaf\VendraCurrency\Support\CurrencyRegistry;

Currency::query()->create([
    'code' => 'USD',
    'name' => CurrencyRegistry::nameFor('USD'),
    'symbol' => '$',
    'decimal_places' => CurrencyRegistry::minorUnitFor('USD'),
    'type' => CurrencyRegistry::typeFor('USD'),
    'is_default' => true,
]);
```

Browse the catalog:

```php
CurrencyRegistry::options();      // ['USD' => 'US Dollar (USD)', ..., 'BTC' => 'BTC', ...]
CurrencyRegistry::isSupported('BTC'); // true
```

Format an amount stored in minor units:

```php
$currency = Currency::query()->where('code', 'USD')->firstOrFail();

$currency->formatAmount(1050); // "$10.50"
$currency->money(1050);        // Cknow\Money\Money instance
```

Exactly one enabled currency is the default at any time; use the domain action to switch it:

```php
use Misaf\VendraCurrency\Actions\SetDefaultCurrencyAction;

(new SetDefaultCurrencyAction())->execute($currency);
```

## Filament

The `Currencies` resource lives in the Sales cluster on the `admin` panel. Install currencies from the searchable catalog, toggle their active state, reorder them, and pick the default via the table toggle or row action.

## Testing

Run the package checks from the project root:

```bash
php artisan test --compact --testsuite=vendra-currency
composer stan
```

## License

MIT. See [LICENSE](LICENSE).
