<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\State;

use ApiPlatform\Metadata\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraAffiliate\Enums\AffiliateStatusEnum;
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraAffiliateApi\ApiResource\AffiliateResource;
use Misaf\VendraApi\State\EloquentResourceProvider;

/**
 * @extends EloquentResourceProvider<Affiliate, AffiliateResource>
 */
final class AffiliateResourceProvider extends EloquentResourceProvider
{
    protected function query(Operation $operation): Builder
    {
        return Affiliate::query()
            ->select(['id', 'code', 'created_at'])
            ->where('status', AffiliateStatusEnum::Active);
    }

    protected function toResource(Model $model, Operation $operation): AffiliateResource
    {
        /** @var Affiliate $model */
        return new AffiliateResource(
            id: $model->id,
            code: $model->code,
            createdAt: $model->created_at->toAtomString(),
        );
    }
}
