<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\McpTool;
use ApiPlatform\Metadata\McpToolCollection;
use Misaf\VendraApi\ApiResource\McpCollectionInput;
use Misaf\VendraApi\ApiResource\McpResourceIdentifierInput;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraBlogApi\State\BlogPostCategoryResourceProvider;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;

#[ApiResource(
    shortName: 'BlogPostCategory',
    operations: [
        new Get(uriTemplate: '/content/blog-post-categories/{id}', provider: BlogPostCategoryResourceProvider::class),
        new GetCollection(uriTemplate: '/content/blog-post-categories', provider: BlogPostCategoryResourceProvider::class),
    ],
    mcp: [
        'get_blog_post_category' => new McpTool(
            description: 'Get an active blog-post category by identifier.',
            input: McpResourceIdentifierInput::class,
            provider: BlogPostCategoryResourceProvider::class,
        ),
        'list_blog_post_categories' => new McpToolCollection(
            description: 'List active blog-post categories.',
            input: McpCollectionInput::class,
            provider: BlogPostCategoryResourceProvider::class,
        ),
    ],
)]
final readonly class BlogPostCategoryResource
{
    /**
     * @param array<string, string> $title
     * @param array<string, string> $slugs
     * @param array<string, string> $description
     * @param array<int, ResourceReference> $blogPosts
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
        public array $blogPosts,
        public array $multimedia,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
