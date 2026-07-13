<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Pennant Features
    |--------------------------------------------------------------------------
    |
    | User Profile module features are tenant-scoped and resolved through
    | Laravel Pennant.
    |
    */

    'features' => [
        'enabled' => env('VENDRA_USER_PROFILE_FEATURES_ENABLED', false),

        'discover' => env('VENDRA_USER_PROFILE_FEATURES_DISCOVER', false),
    ],

];
