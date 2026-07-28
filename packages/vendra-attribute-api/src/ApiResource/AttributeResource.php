<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\BooleanFilter;
use ApiPlatform\Laravel\Eloquent\Filter\PartialSearchFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraAttributeApi\State\AttributeResourceProvider;

#[ApiResource(
    shortName: 'Attribute',
    operations: [
        new Get(uriTemplate: '/catalog/attributes/{id}', provider: AttributeResourceProvider::class),
        new GetCollection(
            uriTemplate: '/catalog/attributes',
            provider: AttributeResourceProvider::class,
            parameters: [
                'q'      => new QueryParameter(key: 'q', property: 'name', filter: PartialSearchFilter::class, constraints: ['string', 'min:2', 'max:100']),
                'active' => new QueryParameter(key: 'active', property: 'active', filter: BooleanFilter::class, constraints: ['boolean']),
            ],
        ),
    ],
)]
final readonly class AttributeResource
{
    /**
     * @param array<int, ResourceReference> $options
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public string $name,
        public ?string $description,
        public ?string $unit,
        public bool $active,
        public array $options,
    ) {}
}
