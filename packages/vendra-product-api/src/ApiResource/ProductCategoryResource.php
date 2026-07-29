<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\ApiResource;

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
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraProductApi\State\ProductCategoryResourceProvider;

#[ApiResource(
    shortName: 'ProductCategory',
    operations: [
        new Get(uriTemplate: '/catalog/product-categories/{id}', provider: ProductCategoryResourceProvider::class),
        new GetCollection(
            uriTemplate: '/catalog/product-categories',
            provider: ProductCategoryResourceProvider::class,
            order: ['position' => 'ASC'],
            parameters: [
                'sort[id]'       => new QueryParameter(key: 'sort[id]', property: 'id', filter: OrderFilter::class),
                'sort[position]' => new QueryParameter(key: 'sort[position]', property: 'position', filter: OrderFilter::class),
            ],
        ),
    ],
    mcp: [
        'get_product_category' => new McpTool(
            description: 'Get an active product category by identifier.',
            input: McpResourceIdentifierInput::class,
            provider: ProductCategoryResourceProvider::class,
        ),
        'list_product_categories' => new McpToolCollection(
            description: 'List active product categories with their titles, slugs, and the products in each.',
            input: McpCollectionInput::class,
            provider: ProductCategoryResourceProvider::class,
        ),
    ],
)]
final readonly class ProductCategoryResource
{
    /**
     * @param array<string, string> $title
     * @param array<string, string> $slugs
     * @param array<string, string> $description
     * @param array<int, ResourceReference> $products
     * @param array<int, MultimediaResource> $multimedia
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $title,
        public array $slugs,
        public array $description,
        public int $position,
        public bool $active,
        public array $products,
        public array $multimedia,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
