<?php

declare(strict_types=1);

return [
    'operator' => [
        'username' => env('CONSOLE_OPERATOR_USERNAME', ''),
        'email'    => env('CONSOLE_OPERATOR_EMAIL', ''),
        'password' => env('CONSOLE_OPERATOR_PASSWORD', ''),
    ],
];
