<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\BooleanFilter;
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
use Misaf\VendraCustomPageApi\State\ContentResourceProvider;

#[ApiResource(
    shortName: 'CustomPage',
    operations: [
        new Get(uriTemplate: '/content/custom-pages/{id}', provider: ContentResourceProvider::class),
        new GetCollection(
            uriTemplate: '/content/custom-pages',
            provider: ContentResourceProvider::class,
            parameters: [
                'active' => new QueryParameter(key: 'active', property: 'active', filter: BooleanFilter::class, constraints: ['boolean']),
            ],
        ),
    ],
    mcp: [
        'get_custom_page' => new McpTool(
            description: 'Get an active custom content page by identifier.',
            input: McpResourceIdentifierInput::class,
            provider: ContentResourceProvider::class,
        ),
        'list_custom_pages' => new McpToolCollection(
            description: 'List active custom content pages.',
            input: McpCollectionInput::class,
            provider: ContentResourceProvider::class,
        ),
    ],
)]
final readonly class CustomPageResource
{
    /**
     * @param array<string, string> $title
     * @param array<string, string> $body
     * @param array<string, string> $slugs
     * @param array<int, ResourceReference> $multimedia
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $title,
        public array $body,
        public array $slugs,
        public ResourceReference $section,
        public array $multimedia,
    ) {}
}
