<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use Misaf\VendraAffiliateApi\State\ReferralCodeProvider;

#[ApiResource(
    shortName: 'Affiliate',
    operations: [
        new Get(
            uriTemplate: '/marketing/affiliates/{id}',
            provider: ReferralCodeProvider::class,
        ),
        new GetCollection(
            uriTemplate: '/marketing/affiliates',
            provider: ReferralCodeProvider::class,
            parameters: [
                'code' => new QueryParameter(
                    key: 'code',
                    property: 'code',
                    filter: EqualsFilter::class,
                    constraints: ['string', 'max:64'],
                ),
                'itemsPerPage' => new QueryParameter(
                    key: 'itemsPerPage',
                    schema: ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
                    constraints: ['integer', 'min:1', 'max:100'],
                ),
            ],
        ),
    ],
    paginationItemsPerPage: 20,
    paginationMaximumItemsPerPage: 100,
)]
final readonly class ReferralCode
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public string $code,
        public string $createdAt,
    ) {}
}
