<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\StorefrontOrigins;
use Closure;
use Fruitcake\Cors\CorsService;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

/**
 * Supplies the canonical API's CORS allowlist at request time.
 *
 * The allowed origins are the active storefront domains — data that changes
 * whenever a property is onboarded — so they cannot be a static config array.
 * They have to be injected here rather than into the CorsService binding
 * because the parent re-applies `config('cors')` over the service on every
 * request, discarding anything set earlier at resolve time.
 */
final class HandleStorefrontCors extends HandleCors
{
    public function __construct(
        Container $container,
        CorsService $cors,
        private readonly StorefrontOrigins $origins,
    ) {
        parent::__construct($container, $cors);
    }

    /**
     * @param  Closure(Request): mixed  $next
     */
    public function handle($request, Closure $next)
    {
        // Only for paths CORS actually covers (api/*), so panel page loads do
        // not pay for a cache lookup they will never use.
        if ($request instanceof Request && $this->hasMatchingPath($request)) {
            Config::set('cors.allowed_origins', $this->origins->all());
        }

        return parent::handle($request, $next);
    }
}
