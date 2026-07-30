<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Panels
    |--------------------------------------------------------------------------
    |
    | These panel IDs determine where developer login shortcuts are available.
    |
    */

    'panels' => ['admin'],

    /*
    |--------------------------------------------------------------------------
    | Developer Login Availability
    |--------------------------------------------------------------------------
    |
    | Developer logins may be disabled entirely or configured to permit
    | switching between eligible users after authentication.
    |
    */

    'enabled' => true,

    'switchable' => true,

    /*
    |--------------------------------------------------------------------------
    | User Identification
    |--------------------------------------------------------------------------
    |
    | The credential column authenticates the selected user, while the label
    | column provides the human-readable value shown in the login interface.
    |
    */

    'column' => 'email',

    'label_column' => 'username',

    /*
    |--------------------------------------------------------------------------
    | Required Role
    |--------------------------------------------------------------------------
    |
    | Set a role name to limit developer logins to that role. A null value
    | falls back to the permission package's configured admin role.
    |
    */

    'role' => null,

];
