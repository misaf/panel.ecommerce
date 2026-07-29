<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\McpTool;
use ApiPlatform\Metadata\McpToolCollection;
use Misaf\VendraApi\ApiResource\McpCollectionInput;
use Misaf\VendraApi\ApiResource\McpResourceIdentifierInput;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraCustomPageApi\State\CustomPageCategoryResourceProvider;

#[ApiResource(
    shortName: 'CustomPageCategory',
    operations: [
        new Get(uriTemplate: '/content/custom-page-categories/{id}', provider: CustomPageCategoryResourceProvider::class),
        new GetCollection(uriTemplate: '/content/custom-page-categories', provider: CustomPageCategoryResourceProvider::class),
    ],
    mcp: [
        'get_custom_page_category' => new McpTool(
            description: 'Get a custom-page category by identifier.',
            input: McpResourceIdentifierInput::class,
            provider: CustomPageCategoryResourceProvider::class,
        ),
        'list_custom_page_categories' => new McpToolCollection(
            description: 'List custom-page categories.',
            input: McpCollectionInput::class,
            provider: CustomPageCategoryResourceProvider::class,
        ),
    ],
)]
final readonly class CustomPageCategoryResource
{
    /**
     * @param array<string, string> $title
     * @param array<int, ResourceReference> $customPages
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $title,
        public array $customPages,
    ) {}
}
