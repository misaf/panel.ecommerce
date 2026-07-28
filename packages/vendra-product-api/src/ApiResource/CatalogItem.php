<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\BooleanFilter;
use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraProductApi\State\ProductResourceProvider;

#[ApiResource(
    shortName: 'Product',
    operations: [
        new Get(uriTemplate: '/catalog/products/{id}', provider: ProductResourceProvider::class),
        new GetCollection(
            uriTemplate: '/catalog/products',
            provider: ProductResourceProvider::class,
            parameters: [
                'inStock' => new QueryParameter(key: 'inStock', property: 'in_stock', filter: BooleanFilter::class, constraints: ['boolean']),
                'groupId' => new QueryParameter(key: 'groupId', property: 'product_category_id', filter: EqualsFilter::class, constraints: ['integer', 'min:1']),
            ],
        ),
    ],
)]
final readonly class CatalogItem
{
    /**
     * @param array<string, string> $title
     * @param array<string, string> $description
     * @param array<int, ResourceReference> $prices
     * @param array<int, ResourceReference> $multimedia
     * @param array<int, ResourceReference> $options
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $title,
        public array $description,
        public int $quantity,
        public bool $inStock,
        public ResourceReference $group,
        public array $prices,
        public array $multimedia,
        public array $options,
    ) {}
}
