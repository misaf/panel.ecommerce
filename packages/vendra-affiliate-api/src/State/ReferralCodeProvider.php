<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\State;

use ApiPlatform\Metadata\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraAffiliate\Enums\AffiliateStatusEnum;
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraAffiliateApi\ApiResource\ReferralCode;
use Misaf\VendraApi\State\EloquentResourceProvider;

/**
 * @extends EloquentResourceProvider<Affiliate, ReferralCode>
 */
final class ReferralCodeProvider extends EloquentResourceProvider
{
    protected function query(Operation $operation): Builder
    {
        return Affiliate::query()
            ->select(['id', 'code', 'created_at'])
            ->where('status', AffiliateStatusEnum::Active);
    }

    protected function toResource(Model $model, Operation $operation): ReferralCode
    {
        /** @var Affiliate $model */
        return new ReferralCode(
            id: $model->id,
            code: $model->code,
            createdAt: $model->created_at->toAtomString(),
        );
    }
}
