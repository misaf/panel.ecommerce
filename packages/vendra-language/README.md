# Vendra Language

Tenant-aware language catalogs and database-backed translation lines for Vendra applications.

## Features

- A platform locale catalog backed by Symfony Intl and ICU
- Per-tenant enabled languages with a default flag and sortable display order
- Database-backed translation lines powered by `spatie/laravel-translation-loader`
- A global Filament language switcher
- An optional bridge to `misaf/vendra-localization`

## Requirements

- PHP 8.3+
- Laravel 13
- Filament 5
- `misaf/vendra-support`
- `symfony/intl`

## Installation

```bash
composer require misaf/vendra-language
php artisan vendor:publish --tag=vendra-language-migrations
php artisan migrate
```

The service provider and Filament plugin are discovered automatically.

## Locale catalog

Configure the platform locales that tenants may enable in `config/vendra-language.php`:

```php
'locales' => ['en', 'de', 'fa', 'pt-BR'],
```

Values use canonical web/BCP-47 tags and must exist in the ICU locale catalog. Tenant language records store only the locale, default flag, and display position; localized names are derived from ICU.

## Usage

Enable a language for the current tenant:

```php
use Misaf\VendraLanguage\Models\Language;

$language = Language::query()->create([
    'locale' => 'en',
    'is_default' => true,
    'position' => 1,
]);
```

Setting another language as default automatically clears the previous default for the current tenant.

Create a translation line and read it through Laravel's translator:

```php
use Misaf\VendraLanguage\Models\LanguageLine;

LanguageLine::query()->create([
    'group' => 'navigation',
    'key' => 'dashboard',
    'text' => [
        'en' => 'Dashboard',
        'de' => 'Übersicht',
        'fa' => 'داشبورد',
    ],
]);

__('navigation.dashboard');
```

To override a package translation, store the package translation namespace separately from the translation file group:

```php
LanguageLine::query()->create([
    'namespace' => 'vendra-product',
    'group' => 'attributes',
    'key' => 'name',
    'text' => ['en' => 'Product name'],
]);

__('vendra-product::attributes.name');
```

Leave `namespace` empty for application translations. In Laravel's `namespace::group.key` syntax, the package name is the namespace and the translation file name is the group.

Load the current tenant's enabled languages in display order:

```php
$languages = Language::query()->ordered()->get();
```

## Optional localization bridge

When `misaf/vendra-localization` is installed, this package supplies its supported locale catalog and appends the tenant-default resolver at the end of the resolver chain. Explicit user, route, query, and header preferences therefore retain priority.

## Filament

The configured panels expose Languages and Language Lines within the Localization cluster. The global language switcher uses the current tenant's enabled languages and falls back to `config('app.fallback_locale')` when none are enabled.

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
