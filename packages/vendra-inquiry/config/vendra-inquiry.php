<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Panels
    |--------------------------------------------------------------------------
    |
    | Here you may specify the Filament panels that the inquiry administration
    | UI is registered on. The inquiry navigation only appears within the
    | panels listed here. You may provide a single panel ID or an array of IDs
    | to mount the module across multiple panels.
    |
    | Supported: "admin" (string), ["admin", "vendor"] (array)
    |
    */

    'panels' => ['admin'],

    /*
    |--------------------------------------------------------------------------
    | Occasions
    |--------------------------------------------------------------------------
    |
    | The occasions a storefront contact form may offer. Keep them as stable
    | slugs: the storefront translates them for display, and an enquiry stores
    | whichever slug the customer picked.
    |
    */

    'occasions' => [
        'wedding',
        'event',
        'sympathy',
        'corporate',
        'other',
    ],

];
