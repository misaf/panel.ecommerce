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

    'features_enabled' => env('VENDRA_USER_PROFILE_FEATURES_ENABLED', true),

    'features_discover' => env('VENDRA_USER_PROFILE_FEATURES_DISCOVER', false),

    'module_enabled' => env('VENDRA_USER_PROFILE_MODULE_ENABLED', true),

];
