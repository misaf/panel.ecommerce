<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS)
    |--------------------------------------------------------------------------
    |
    | Only the canonical API is cross-origin. The admin, console and reseller
    | panels are same-origin Livewire apps and must NOT be exposed here.
    |
    | `allowed_origins` is intentionally empty: the real allowlist is the set of
    | active storefront domains, which is data rather than configuration. It is
    | injected at request time by App\Http\Middleware\HandleStorefrontCors from
    | Misaf\VendraStore\Support\StorefrontOrigins. An empty list denies every cross-origin
    | call, so a misconfiguration fails closed.
    |
    */

    'paths' => ['api/*'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'Origin',
        'X-Requested-With',
        'X-Request-ID',
    ],

    // Echoed back so a storefront can read the correlation id of its own call.
    'exposed_headers' => ['X-Request-ID'],

    'max_age' => 3600,

    /*
     | Storefront calls are not cookie-authenticated: the API is stateless and a
     | per-storefront credential is the intended mechanism. Keep this false until
     | that lands — flipping it on requires an exact-origin echo (never `*`),
     | which the allowlist above already guarantees.
     */
    'supports_credentials' => false,

];
