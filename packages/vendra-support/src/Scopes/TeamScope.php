<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Misaf\VendraSupport\Contracts\TenantResolver;
use Misaf\VendraSupport\Support\TenantSchema;

/**
 * @implements Scope<Model>
 */
class TeamScope implements Scope
{
    /**
     * @param Builder<covariant Model> $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model): void
    {
        if ( ! TenantSchema::hasTenantColumn($model->getTable())) {
            return;
        }

        if ( ! app()->bound(TenantResolver::class) || null !== app(TenantResolver::class)->current()) {
            return;
        }

        $user = auth()->user();

        if ( ! $user instanceof Model) {
            return;
        }

        if ( ! array_key_exists('tenant_id', $user->getAttributes())) {
            return;
        }

        $tenantId = $user->getAttribute('tenant_id');

        if (is_int($tenantId) || is_string($tenantId)) {
            $builder->where($model->qualifyColumn('tenant_id'), $tenantId);
        }
    }
}
