<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Laravel\Eloquent\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\McpTool;
use ApiPlatform\Metadata\McpToolCollection;
use ApiPlatform\Metadata\QueryParameter;
use Misaf\VendraApi\ApiResource\McpCollectionInput;
use Misaf\VendraApi\ApiResource\McpResourceIdentifierInput;
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
                'currency'      => new QueryParameter(key: 'currency', property: 'currency_code', filter: EqualsFilter::class, constraints: ['string', 'size:3']),
                'productId'     => new QueryParameter(key: 'productId', property: 'product_id', filter: EqualsFilter::class, constraints: ['integer', 'min:1']),
                'sort[id]'      => new QueryParameter(key: 'sort[id]', property: 'id', filter: OrderFilter::class),
                'sort[price]'   => new QueryParameter(key: 'sort[price]', property: 'price', filter: OrderFilter::class),
            ],
        ),
    ],
    mcp: [
        'get_product_price' => new McpTool(
            description: 'Get a product price by identifier.',
            input: McpResourceIdentifierInput::class,
            provider: ProductResourceProvider::class,
        ),
        'list_product_prices' => new McpToolCollection(
            description: 'List product prices with their minor-unit amount, currency, formatted value, and linked product.',
            input: McpCollectionInput::class,
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
        public float|int $amount,
        public string $currency,
        public string $formatted,
        public ResourceReference $product,
    ) {}
}
