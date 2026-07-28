<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\McpToolCollection;
use ApiPlatform\Metadata\QueryParameter;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraProductApi\State\ProductResourceProvider;

#[ApiResource(
    shortName: 'ProductPrice',
    operations: [
        new Get(uriTemplate: '/catalog/product-prices/{id}', provider: ProductResourceProvider::class),
        new GetCollection(
            uriTemplate: '/catalog/product-prices',
            provider: ProductResourceProvider::class,
            parameters: [
                'currency' => new QueryParameter(key: 'currency', property: 'currency_code', filter: EqualsFilter::class, constraints: ['string', 'size:3']),
                'itemId'   => new QueryParameter(key: 'itemId', property: 'product_id', filter: EqualsFilter::class, constraints: ['integer', 'min:1']),
            ],
        ),
    ],
    mcp: [
        'list_product_prices' => new McpToolCollection(
            description: 'List product prices with their minor-unit amount, currency, formatted value, and linked product.',
            input: CatalogListingInput::class,
            provider: ProductResourceProvider::class,
        ),
    ],
)]
final readonly class ProductPriceResource
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public int $minorAmount,
        public string $currency,
        public string $formatted,
        public ResourceReference $item,
    ) {}
}
