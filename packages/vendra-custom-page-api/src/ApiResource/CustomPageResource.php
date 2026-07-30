<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\BooleanFilter;
use ApiPlatform\Laravel\Eloquent\State\Options;
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
use Misaf\VendraCustomPage\Models\CustomPage;
use Misaf\VendraCustomPageApi\State\CustomPageResourceProvider;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;

#[ApiResource(
    shortName: 'CustomPage',
    stateOptions: new Options(modelClass: CustomPage::class, handleLinks: CustomPageResourceProvider::class),
    mcp: [
        'get_custom_page' => new McpTool(
            description: 'Get an active custom content page by identifier.',
            input: McpResourceIdentifierInput::class,
            provider: CustomPageResourceProvider::class,
        ),
        'list_custom_pages' => new McpToolCollection(
            description: 'List active custom content pages.',
            input: McpCollectionInput::class,
            provider: CustomPageResourceProvider::class,
        ),
    ],
)]
#[Get(uriTemplate: '/content/custom-pages/{id}', provider: CustomPageResourceProvider::class)]
#[GetCollection(
    uriTemplate: '/content/custom-pages',
    provider: CustomPageResourceProvider::class,
    parameters: [
        'active' => new QueryParameter(key: 'active', property: 'active', filter: BooleanFilter::class, constraints: ['boolean']),
    ],
)]
final readonly class CustomPageResource
{
    /**
     * @param array<string, string> $title
     * @param array<string, string> $body
     * @param array<string, string> $slugs
     * @param array<int, MultimediaResource> $multimedia
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $title,
        public array $body,
        public array $slugs,
        public ResourceReference $customPageCategory,
        public array $multimedia,
    ) {}
}
