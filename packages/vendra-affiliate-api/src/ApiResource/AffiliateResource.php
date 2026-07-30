<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Laravel\Eloquent\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\McpTool;
use ApiPlatform\Metadata\McpToolCollection;
use ApiPlatform\Metadata\QueryParameter;
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraAffiliateApi\State\AffiliateResourceProvider;
use Misaf\VendraApi\ApiResource\McpCollectionInput;
use Misaf\VendraApi\ApiResource\McpResourceIdentifierInput;

#[ApiResource(
    shortName: 'Affiliate',
    stateOptions: new Options(
        modelClass: Affiliate::class,
        handleLinks: AffiliateResourceProvider::class,
    ),
    mcp: [
        'get_affiliate' => new McpTool(
            description: 'Get an active affiliate referral code by its identifier.',
            input: McpResourceIdentifierInput::class,
            provider: AffiliateResourceProvider::class,
        ),
        'list_affiliates' => new McpToolCollection(
            description: 'List active affiliate referral codes.',
            input: McpCollectionInput::class,
            provider: AffiliateResourceProvider::class,
        ),
    ],
)]
#[Get(uriTemplate: '/marketing/affiliates/{id}', provider: AffiliateResourceProvider::class)]
#[GetCollection(
    uriTemplate: '/marketing/affiliates',
    provider: AffiliateResourceProvider::class,
    parameters: [
        'itemsPerPage' => new QueryParameter(
            key: 'itemsPerPage',
            constraints: ['integer', 'min:1', 'max:100'],
        ),
        'code' => new QueryParameter(
            key: 'code',
            property: 'code',
            filter: EqualsFilter::class,
            constraints: ['string', 'max:64'],
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
