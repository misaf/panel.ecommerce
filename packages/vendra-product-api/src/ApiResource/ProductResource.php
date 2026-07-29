<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\BooleanFilter;
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
use Misaf\VendraApi\Eloquent\Filter\LocalizedEqualsFilter;
use Misaf\VendraApi\Eloquent\Filter\LocalizedSearchFilter;
use Misaf\VendraApi\Eloquent\Filter\RandomOrderFilter;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraProductApi\State\ProductResourceProvider;

#[ApiResource(
    shortName: 'Product',
    operations: [
        new Get(uriTemplate: '/catalog/products/{id}', provider: ProductResourceProvider::class),
        new GetCollection(
            uriTemplate: '/catalog/products',
            provider: ProductResourceProvider::class,
            order: ['position' => 'ASC'],
            parameters: [
                'inStock'         => new QueryParameter(key: 'inStock', property: 'in_stock', filter: BooleanFilter::class, constraints: ['boolean']),
                'categoryId'      => new QueryParameter(key: 'categoryId', property: 'product_category_id', filter: EqualsFilter::class, constraints: ['integer', 'min:1']),
                'token'           => new QueryParameter(key: 'token', property: 'token', filter: EqualsFilter::class, constraints: ['string', 'max:255']),
                'slug'            => new QueryParameter(key: 'slug', property: 'slug', filter: LocalizedEqualsFilter::class, constraints: ['string', 'max:255']),
                'search'          => new QueryParameter(
                    key: 'search',
                    filter: LocalizedSearchFilter::class,
                    filterContext: ['properties' => ['name' => true, 'slug' => true, 'token' => false]],
                    constraints: ['string', 'max:255'],
                ),
                'sort[id]'        => new QueryParameter(key: 'sort[id]', property: 'id', filter: OrderFilter::class),
                'sort[position]'  => new QueryParameter(key: 'sort[position]', property: 'position', filter: OrderFilter::class),
                'sort[createdAt]' => new QueryParameter(key: 'sort[createdAt]', property: 'created_at', filter: OrderFilter::class),
                'random'          => new QueryParameter(
                    key: 'random',
                    filter: RandomOrderFilter::class,
                    constraints: ['boolean'],
                    castToNativeType: true,
                ),
            ],
        ),
    ],
    mcp: [
        'get_product' => new McpTool(
            description: 'Get an active catalog product with its category, prices, media, and attribute options by identifier.',
            input: McpResourceIdentifierInput::class,
            provider: ProductResourceProvider::class,
        ),
        'list_products' => new McpToolCollection(
            description: 'List active catalog products with their categories, prices, media, and attribute options.',
            input: McpCollectionInput::class,
            provider: ProductResourceProvider::class,
        ),
    ],
)]
final readonly class ProductResource
{
    /**
     * @param array<string, string> $title
     * @param array<string, string> $slugs
     * @param array<string, string> $description
     * @param array<int, ProductPriceResource> $productPrices
     * @param array<int, MultimediaResource> $multimedia
     * @param array<int, ResourceReference> $options
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $title,
        public array $slugs,
        public array $description,
        public string $token,
        public int $quantity,
        public bool $inStock,
        public ResourceReference $productCategory,
        public array $productPrices,
        public ?ProductPriceResource $latestProductPrice,
        public array $multimedia,
        public array $options,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
