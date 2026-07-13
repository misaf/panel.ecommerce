<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Panels
    |--------------------------------------------------------------------------
    |
    | Here you may specify the Filament panels that the cart administration UI
    | is registered on. The cart navigation only appears within the panels
    | listed here. You may provide a single panel ID or an array of IDs to
    | mount the module across multiple panels.
    |
    | Supported: "admin" (string), ["admin", "vendor"] (array)
    |
    */

    'panels' => ['admin'],

    /*
    |--------------------------------------------------------------------------
    | Cart Expiry
    |--------------------------------------------------------------------------
    |
    | Here you may specify the number of days a newly created cart remains
    | valid when no explicit expiry is provided. Expired carts are removed
    | by the scheduled vendra-cart:prune-expired command. Set this to null
    | to create carts without an expiry by default.
    |
    */

    'expires_after_days' => 7,

];
