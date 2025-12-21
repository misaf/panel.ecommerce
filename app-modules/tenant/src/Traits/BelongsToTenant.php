<?php

declare(strict_types=1);

namespace Misaf\Tenant\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Misaf\Tenant\Models\Tenant;
use Misaf\Tenant\Scopes\TenantScope;

trait BelongsToTenant
{
    /**
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * @return void
     */
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model): void {
            if ($tenantId = static::getCurrentTenantId()) {
                $model->tenant_id = $tenantId;
            }
        });
    }

    /**
     * @return int|null
     */
    private static function getCurrentTenantId(): ?int
    {
        return app()->has('currentTenant') ? Tenant::current()->id : null;
    }
}
