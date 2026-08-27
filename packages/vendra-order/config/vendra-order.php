<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Panels
    |--------------------------------------------------------------------------
    |
    | Here you may specify the Filament panels that the order administration UI
    | is registered on. The order navigation only appears within the panels
    | listed here. You may provide a single panel ID or an array of IDs to
    | mount the module across multiple panels.
    |
    | Supported: "admin" (string), ["admin", "vendor"] (array)
    |
    */

    'panels' => ['admin'],

    /*
    |--------------------------------------------------------------------------
    | Order Number Prefix
    |--------------------------------------------------------------------------
    |
    | Every order receives a human-readable number that customers quote back to
    | support. The prefix is combined with a random suffix when the order is
    | created without an explicit number.
    |
    */

    'number_prefix' => env('ORDER_NUMBER_PREFIX', 'ORD'),

];
