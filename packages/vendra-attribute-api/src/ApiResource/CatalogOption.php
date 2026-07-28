<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraAttributeApi\State\AttributeResourceProvider;

#[ApiResource(
    shortName: 'AttributeValue',
    operations: [
        new Get(uriTemplate: '/catalog/attribute-values/{id}', provider: AttributeResourceProvider::class),
        new GetCollection(
            uriTemplate: '/catalog/attribute-values',
            provider: AttributeResourceProvider::class,
            parameters: [
                'attributeId' => new QueryParameter(key: 'attributeId', property: 'attribute_id', filter: EqualsFilter::class, constraints: ['integer', 'min:1']),
            ],
        ),
    ],
)]
final readonly class CatalogOption
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public string $value,
        public ResourceReference $attribute,
    ) {}
}
