<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\McpTool;
use ApiPlatform\Metadata\McpToolCollection;
use Misaf\VendraApi\ApiResource\McpCollectionInput;
use Misaf\VendraApi\ApiResource\McpResourceIdentifierInput;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraProductApi\State\ProductResourceProvider;

#[ApiResource(
    shortName: 'ProductCategory',
    operations: [
        new Get(uriTemplate: '/catalog/product-categories/{id}', provider: ProductResourceProvider::class),
        new GetCollection(uriTemplate: '/catalog/product-categories', provider: ProductResourceProvider::class),
    ],
    mcp: [
        'get_product_category' => new McpTool(
            description: 'Get an active product category by identifier.',
            input: McpResourceIdentifierInput::class,
            provider: ProductResourceProvider::class,
        ),
        'list_product_categories' => new McpToolCollection(
            description: 'List active product categories with their titles, slugs, and the products in each.',
            input: McpCollectionInput::class,
            provider: ProductResourceProvider::class,
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
