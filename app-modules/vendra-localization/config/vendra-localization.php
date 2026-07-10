<?php

declare(strict_types=1);

use Misaf\VendraLocalization\Resolvers\AcceptLanguageLocaleResolver;
use Misaf\VendraLocalization\Resolvers\UserLocaleResolver;

return [

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | The locales your application serves. A resolved locale is validated
    | against this list; region variants (fr-CA) match their base
    | language (fr). Anything else falls back to app.fallback_locale.
    |
    */

    'supported_locales' => ['en'],

    /*
    |--------------------------------------------------------------------------
    | Locale Resolvers
    |--------------------------------------------------------------------------
    |
    | The resolver chain, tried in order: the first resolver returning a
    | locale wins. Each entry is a class implementing
    | Misaf\VendraLocalization\Contracts\LocaleResolver.
    |
    */

    'resolvers' => [
        UserLocaleResolver::class,
        AcceptLanguageLocaleResolver::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Locale Syncing
    |--------------------------------------------------------------------------
    |
    | Also apply the resolved locale to Carbon dates and the Number
    | helper (the latter requires ext-intl).
    |
    */

    'sync' => [
        'carbon' => false,
        'number' => false,
    ],

];
