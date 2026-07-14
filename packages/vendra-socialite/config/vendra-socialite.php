<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Panels
    |--------------------------------------------------------------------------
    |
    | The Filament panels that expose OAuth social login. Google and GitHub
    | buttons render on the login form of each panel listed here and only
    | appear when their `services.<provider>` credentials are present.
    |
    | Supported: "admin" (string), ["admin", "user"] (array)
    |
    */

    'panels' => ['admin'],

    /*
    |--------------------------------------------------------------------------
    | Registration
    |--------------------------------------------------------------------------
    |
    | When true, an unknown OAuth identity creates a new user; when false, only
    | existing users (matched by email) may sign in.
    |
    */

    'registration' => env('VENDRA_SOCIALITE_REGISTRATION', false),

    /*
    |--------------------------------------------------------------------------
    | Domain Allow List
    |--------------------------------------------------------------------------
    |
    | Optional email domains permitted to sign in. Empty means all domains are
    | allowed. Accepts an array or a comma-separated environment value.
    |
    */

    'domain_allow_list' => env('VENDRA_SOCIALITE_DOMAINS', []),

];
