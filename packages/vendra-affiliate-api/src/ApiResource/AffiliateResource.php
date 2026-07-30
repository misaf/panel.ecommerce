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
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraAffiliateApi\State\AffiliateLinksHandler;
use Misaf\VendraAffiliateApi\State\AffiliateMapper;
use Misaf\VendraApi\ApiResource\McpCollectionInput;
use Misaf\VendraApi\ApiResource\McpResourceIdentifierInput;
use Misaf\VendraApi\State\EloquentResourceOptions;
use Misaf\VendraApi\State\EloquentResourceProvider;

#[ApiResource(
    shortName: 'Affiliate',
    provider: EloquentResourceProvider::class,
    stateOptions: new EloquentResourceOptions(
        modelClass: Affiliate::class,
        handleLinks: AffiliateLinksHandler::class,
        mapper: AffiliateMapper::class,
    ),
    mcp: [
        'get_affiliate' => new McpTool(
            description: 'Get an active affiliate referral code by its identifier.',
            input: McpResourceIdentifierInput::class,
            provider: EloquentResourceProvider::class,
        ),
        'list_affiliates' => new McpToolCollection(
            description: 'List active affiliate referral codes.',
            input: McpCollectionInput::class,
            provider: EloquentResourceProvider::class,
        ),
    ],
)]
#[Get(uriTemplate: '/marketing/affiliates/{id}')]
#[GetCollection(
    uriTemplate: '/marketing/affiliates',
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
