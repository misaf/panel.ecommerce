<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
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
use Misaf\VendraApi\State\EloquentResourceOptions;
use Misaf\VendraApi\State\EloquentResourceProvider;
use Misaf\VendraAttribute\Models\AttributeValue;
use Misaf\VendraAttributeApi\State\AttributeValueLinksHandler;
use Misaf\VendraAttributeApi\State\AttributeValueMapper;

#[ApiResource(
    shortName: 'AttributeValue',
    provider: EloquentResourceProvider::class,
    stateOptions: new EloquentResourceOptions(
        modelClass: AttributeValue::class,
        handleLinks: AttributeValueLinksHandler::class,
        mapper: AttributeValueMapper::class,
    ),
    mcp: [
        'get_attribute_value' => new McpTool(
            description: 'Get an active catalog attribute value by identifier.',
            input: McpResourceIdentifierInput::class,
            provider: EloquentResourceProvider::class,
        ),
        'list_attribute_values' => new McpToolCollection(
            description: 'List values belonging to active catalog attributes.',
            input: McpCollectionInput::class,
            provider: EloquentResourceProvider::class,
        ),
    ],
)]
#[Get(uriTemplate: '/catalog/attribute-values/{id}')]
#[GetCollection(
    uriTemplate: '/catalog/attribute-values',
    parameters: [
        'attributeId' => new QueryParameter(key: 'attributeId', property: 'attribute_id', filter: EqualsFilter::class, constraints: ['integer', 'min:1']),
    ],
)]
final readonly class AttributeValueResource
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public string $value,
        public ResourceReference $attribute,
    ) {}
}
