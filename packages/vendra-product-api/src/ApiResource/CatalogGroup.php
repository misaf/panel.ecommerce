<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraProductApi\State\ProductResourceProvider;

#[ApiResource(
    shortName: 'ProductCategory',
    operations: [
        new Get(uriTemplate: '/catalog/product-categories/{id}', provider: ProductResourceProvider::class),
        new GetCollection(uriTemplate: '/catalog/product-categories', provider: ProductResourceProvider::class),
    ],
)]
final readonly class CatalogGroup
{
    /**
     * @param array<string, string> $title
     * @param array<string, string> $slugs
     * @param array<int, ResourceReference> $items
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $title,
        public array $slugs,
        public array $items,
    ) {}
}
