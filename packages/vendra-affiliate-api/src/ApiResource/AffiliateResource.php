<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\McpTool;
use ApiPlatform\Metadata\McpToolCollection;
use ApiPlatform\Metadata\QueryParameter;
use Misaf\VendraAffiliateApi\State\ReferralCodeProvider;
use Misaf\VendraApi\ApiResource\McpCollectionInput;
use Misaf\VendraApi\ApiResource\McpResourceIdentifierInput;

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
            ],
        ),
    ],
    mcp: [
        'get_affiliate' => new McpTool(
            description: 'Get an active affiliate referral code by its identifier.',
            input: McpResourceIdentifierInput::class,
            provider: ReferralCodeProvider::class,
        ),
        'list_affiliates' => new McpToolCollection(
            description: 'List active affiliate referral codes.',
            input: McpCollectionInput::class,
            provider: ReferralCodeProvider::class,
        ),
    ],
)]
final readonly class AffiliateResource
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public string $code,
        public string $createdAt,
    ) {}
}
