<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\ResellerUser;
use Closure;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Http\Request;
use Misaf\VendraSupport\Context\RequestJobContext;
use Misaf\VendraTenant\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

final readonly class AddResellerToRequestJobContext
{
    public function __construct(private Factory $auth) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $resellerUser = $this->auth->guard('reseller')->user();
        $tenant = Tenant::current();
        $resellerId = $resellerUser instanceof ResellerUser
            ? $resellerUser->reseller_id
            : ($tenant instanceof Tenant ? $tenant->reseller_id : null);

        (new RequestJobContext(resellerId: $resellerId))->add();

        return $next($request);
    }
}
