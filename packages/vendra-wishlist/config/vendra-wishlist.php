<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Panels
    |--------------------------------------------------------------------------
    |
    | Here you may specify the Filament panels that the wishlist administration
    | UI is registered on. The wishlist navigation only appears within the
    | panels listed here. You may provide a single panel ID or an array of IDs
    | to mount the module across multiple panels.
    |
    | Supported: "admin" (string), ["admin", "vendor"] (array)
    |
    */

    'panels' => ['admin'],

    /*
    |--------------------------------------------------------------------------
    | Default List Name
    |--------------------------------------------------------------------------
    |
    | The name given to the list a heart button writes to when a customer has
    | not created one themselves.
    |
    */

    'default_name' => env('WISHLIST_DEFAULT_NAME', 'Favourites'),

];
