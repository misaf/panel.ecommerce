<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\JsonApi\V1\Affiliates;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Misaf\VendraAffiliate\Models\Affiliate;

/**
 * @mixin Affiliate
 */
final class AffiliateResource extends JsonApiResource
{
    /**
     * @return iterable<string, mixed>
     */
    public function attributes($request): iterable
    {
        return [
            'code'       => $this->code,
            'created_at' => $this->created_at,
        ];
    }
}
