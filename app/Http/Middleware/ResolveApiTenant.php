<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Misaf\VendraSupport\Contracts\TenantResolver;
use Misaf\VendraTenant\Services\DomainTenantFinder;
use Spatie\Multitenancy\Contracts\IsTenant;
use Symfony\Component\HttpFoundation\Response;

final readonly class ResolveApiTenant
{
    public function __construct(
        private DomainTenantFinder $tenantFinder,
        private TenantResolver $tenantResolver,
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var IsTenant|null $resolvedTenant */
        $resolvedTenant = null;

        if (null === $this->tenantResolver->current()) {
            $tenant = $this->tenantFinder->findForRequest($request);

            abort_if(null === $tenant, Response::HTTP_NOT_FOUND);

            $tenant->makeCurrent();
            $resolvedTenant = $tenant;
        }

        try {
            return $next($request);
        } finally {
            $resolvedTenant?->forget();
        }
    }
}
