<?php

declare(strict_types=1);

namespace App\Models\Tenant\Services;

use App\Models\Tenant\Tenant;
use Illuminate\Http\Request;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

final class DomainTenantFinderService extends TenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        return Tenant::where('domain', $request->getHost())->first();
        // return Tenant::whereHas('tenantDomains', fn($builder) => $builder->where('name', $request->getHost())->where('status', 'Enable'))->where('status', 'Enable')->first();
    }
}
