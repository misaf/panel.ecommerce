<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Namespace
    |--------------------------------------------------------------------------
    |
    | This is the PHP namespace that your modules will be created in. For
    | example, a module called "Helpers" will be placed in \Modules\Helpers
    | by default.
    |
    | It is *highly recommended* that you configure this to your organization
    | name to make extracting modules to their own package easier (should you
    | choose to ever do so).
    |
    | If you set the namespace, you should also set the vendor name to match.
    |
    */

    'model' => Misaf\VendraAuthifyLog\Models\AuthifyLog::class,

    'notifications' => [
        'authenticated' => Misaf\VendraAuthifyLog\Notifications\LoginNotification::class,

        'attempting' => Misaf\VendraAuthifyLog\Notifications\LoginNotification::class,

        'currentDeviceLogout' => Misaf\VendraAuthifyLog\Notifications\LoginNotification::class,

        'failed' => Misaf\VendraAuthifyLog\Notifications\LoginNotification::class,

        'lockout' => Misaf\VendraAuthifyLog\Notifications\LoginNotification::class,

        'otherDeviceLogout' => Misaf\VendraAuthifyLog\Notifications\LoginNotification::class,

        'passwordReset' => Misaf\VendraAuthifyLog\Notifications\LoginNotification::class,

        'registered' => Misaf\VendraAuthifyLog\Notifications\LoginNotification::class,

        'logout' => Misaf\VendraAuthifyLog\Notifications\LoginNotification::class,

        'validated' => Misaf\VendraAuthifyLog\Notifications\LoginNotification::class,

        'verified' => Misaf\VendraAuthifyLog\Notifications\LoginNotification::class,

        'login' => Misaf\VendraAuthifyLog\Notifications\LoginNotification::class,
    ]

];
