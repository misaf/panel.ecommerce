<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Allowed Domains
    |--------------------------------------------------------------------------
    |
    | Restricts the domains accepted by the EmailValidation rule. Leave this
    | empty to impose no domain restriction.
    |
    */

    'allowed_domains' => [
        'gmail.com',
        'yahoo.com',
        'yahoo.de',
        'hotmail.com',
        'outlook.com',
        'aol.com',
        'icloud.com',
        'protonmail.com',
        'mail.com',
        'gmx.com',
        'live.com',
        'msn.com',
        'ymail.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Deliverability Driver
    |--------------------------------------------------------------------------
    |
    | Verifies deliverability in production via the Emailable API, while local
    | and testing environments use the "null" driver (no external check).
    | Override with the EMAIL_VERIFIER_DRIVER environment variable.
    |
    | Supported: "null", "emailable"
    |
    */

    'default' => env('EMAIL_VERIFIER_DRIVER', 'production' === env('APP_ENV') ? 'emailable' : 'null'),

];
