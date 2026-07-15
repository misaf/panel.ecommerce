<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Panels
    |--------------------------------------------------------------------------
    |
    | These panels receive Vendra's shared domain clusters.
    |
    */

    'panels' => ['admin'],

    /*
    |--------------------------------------------------------------------------
    | Sandbox Mode
    |--------------------------------------------------------------------------
    |
    | Enable this when the application should behave as a sandbox
    | environment. Runtime code should read this value via the support
    | layer instead of checking environment variables directly.
    |
    */

    'sandbox' => env('VENDRA_SANDBOX', false),

];
