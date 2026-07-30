<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\State;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraAffiliateApi\ApiResource\AffiliateResource;
use Misaf\VendraApi\State\ResourceMapper;
use UnexpectedValueException;

final class AffiliateMapper implements ResourceMapper
{
    public function map(Model $model): AffiliateResource
    {
        if ( ! $model instanceof Affiliate) {
            throw new UnexpectedValueException('Expected an affiliate model.');
        }

        return new AffiliateResource(
            id: $model->id,
            code: $model->code,
            createdAt: $model->created_at->toAtomString(),
        );
    }
}
