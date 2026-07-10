# Vendra Localization

Request locale resolution for Laravel applications.

## Features

- `SetLocale` middleware that resolves the request locale, calls `App::setLocale()`, and sets the `Content-Language` and `Vary` response headers
- Config-driven resolver chain: the first resolver that produces a locale wins
- Built-in resolvers for the `Accept-Language` header, query string, route parameter, and authenticated user (including Laravel's `HasLocalePreference` contract)
- Validates the resolved locale against the supported locales, matching region variants (`fr-CA` matches a supported `fr`)
- Falls back to `app.fallback_locale` whenever the resolved locale is missing or unsupported
- Sends `Vary: Accept-Language` only when the locale actually derives from the header
- Optional syncing of the resolved locale to `Carbon` dates and the `Number` helper

## Requirements

- PHP 8.3+
- Laravel 12 or 13
- Pest 4

## Installation

```bash
composer require misaf/vendra-localization
```

Publish the config file:

```bash
php artisan vendor:publish --tag=vendra-localization-config
```

The service provider is auto-registered.

## Configuration

`config/vendra-localization.php`:

```php
return [
    // The locales your application serves. Region variants (fr-CA)
    // match their base language (fr).
    'supported_locales' => ['en', 'fa', 'de'],

    // Tried in order; the first resolver returning a locale wins.
    'resolvers' => [
        Misaf\VendraLocalization\Resolvers\UserLocaleResolver::class,
        Misaf\VendraLocalization\Resolvers\AcceptLanguageLocaleResolver::class,
    ],

    // Also apply the resolved locale to Carbon dates and the Number
    // helper (the latter requires ext-intl).
    'sync' => [
        'carbon' => false,
        'number' => false,
    ],
];
```

## Usage

Register the middleware on the group or routes that should be localized, e.g. in `bootstrap/app.php`:

```php
use Misaf\VendraLocalization\Http\Middleware\SetLocale;

->withMiddleware(function (Middleware $middleware): void {
    $middleware->web(append: [
        SetLocale::class,
    ]);
})
```

On each request the middleware resolves a locale through the configured resolver chain, validates it against `supported_locales`, applies it with `App::setLocale()`, and adds the response headers:

```
Content-Language: fa
Vary: Accept-Language
```

`Vary: Accept-Language` is only added when the chain includes the `Accept-Language` resolver, so cache keys stay accurate for query- or route-based strategies.

### Per-route resolver chains

The package registers a `vendra.locale` middleware alias that accepts resolver aliases (or class names) as parameters, overriding the configured chain for those routes:

```php
Route::middleware('vendra.locale:route,accept-language')->group(function (): void {
    // ...
});
```

## Resolvers

| Resolver | Alias | Reads the locale from |
| --- | --- | --- |
| `AcceptLanguageLocaleResolver` | `accept-language` | The `Accept-Language` request header |
| `QueryLocaleResolver` | `query` | The `?locale=` query string parameter |
| `RouteLocaleResolver` | `route` | The `{locale}` route parameter |
| `UserLocaleResolver` | `user` | The authenticated user: `preferredLocale()` when the user implements `Illuminate\Contracts\Translation\HasLocalePreference`, otherwise the `locale` attribute |

Resolvers return `null` when their source has no value; the chain then moves to the next resolver, and the `SetLocale` middleware applies `app.fallback_locale` when the whole chain comes up empty.

## Custom resolver

Implement the `LocaleResolver` contract and add it to the `resolvers` chain:

```php
use Illuminate\Http\Request;
use Misaf\VendraLocalization\Contracts\LocaleResolver;

final readonly class TenantLocaleResolver implements LocaleResolver
{
    public function resolve(Request $request): ?string
    {
        return $request->user()?->tenant?->locale;
    }
}
```

```php
// config/vendra-localization.php
'resolvers' => [
    App\Localization\TenantLocaleResolver::class,
    Misaf\VendraLocalization\Resolvers\AcceptLanguageLocaleResolver::class,
],
```

The resolved locale is still validated against `supported_locales` by the middleware, so an unsupported value falls back automatically. Return `null` to defer to the next resolver in the chain.

If your resolver reads request headers, also implement `Misaf\VendraLocalization\Contracts\ProvidesVaryHeaders` so the middleware can advertise them in the response `Vary` header.

## Events

Laravel dispatches `Illuminate\Foundation\Events\LocaleUpdated` whenever `App::setLocale()` runs — listen for that event to react to locale changes; the package does not add an event of its own.

## Testing

```bash
composer test
```

## License

MIT.
