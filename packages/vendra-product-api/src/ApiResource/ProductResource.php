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
use ApiPlatform\Metadata\McpToolCollection;
use ApiPlatform\Metadata\QueryParameter;
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
                'groupId'         => new QueryParameter(key: 'groupId', property: 'product_category_id', filter: EqualsFilter::class, constraints: ['integer', 'min:1']),
                'token'           => new QueryParameter(key: 'token', property: 'token', filter: EqualsFilter::class, constraints: ['string', 'max:255']),
                'slug'            => new QueryParameter(key: 'slug', property: 'slug', filter: new LocalizedEqualsFilter(), constraints: ['string', 'max:255']),
                'search'          => new QueryParameter(
                    key: 'search',
                    filter: new LocalizedSearchFilter(),
                    filterContext: ['properties' => ['name' => true, 'slug' => true, 'token' => false]],
                    constraints: ['string', 'max:255'],
                ),
                'sort[id]'        => new QueryParameter(key: 'sort[id]', property: 'id', filter: OrderFilter::class),
                'sort[position]'  => new QueryParameter(key: 'sort[position]', property: 'position', filter: OrderFilter::class),
                'sort[createdAt]' => new QueryParameter(key: 'sort[createdAt]', property: 'created_at', filter: OrderFilter::class),
                'random'          => new QueryParameter(
                    key: 'random',
                    filter: new RandomOrderFilter(),
                    constraints: ['boolean'],
                    castToNativeType: true,
                ),
            ],
        ),
    ],
    mcp: [
        'list_products' => new McpToolCollection(
            description: 'List active catalog products with their categories, prices, media, and attribute options.',
            input: CatalogListingInput::class,
            provider: ProductResourceProvider::class,
        ),
    ],
)]
final readonly class ProductResource
{
    /**
     * @param array<string, string> $title
     * @param array<string, string> $slug
     * @param array<string, string> $description
     * @param array<int, ProductPriceResource> $productPrices
     * @param array<int, MultimediaResource> $multimedia
     * @param array<int, ResourceReference> $options
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $title,
        public array $slug,
        public array $description,
        public string $token,
        public int $quantity,
        public bool $inStock,
        public ProductCategoryResource $productCategory,
        public array $productPrices,
        public ?ProductPriceResource $latestProductPrice,
        public array $multimedia,
        public array $options,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
