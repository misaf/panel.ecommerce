<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\BooleanFilter;
use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Laravel\Eloquent\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\McpTool;
use ApiPlatform\Metadata\McpToolCollection;
use ApiPlatform\Metadata\QueryParameter;
use Misaf\VendraApi\ApiResource\McpCollectionInput;
use Misaf\VendraApi\ApiResource\McpResourceIdentifierInput;
use Misaf\VendraApi\Eloquent\Filter\LocalizedEqualsFilter;
use Misaf\VendraApi\Eloquent\Filter\LocalizedSearchFilter;
use Misaf\VendraBlogApi\State\BlogResourceProvider;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;

#[ApiResource(
    shortName: 'BlogPost',
    operations: [
        new Get(uriTemplate: '/content/blog-posts/{id}', provider: BlogResourceProvider::class),
        new GetCollection(
            uriTemplate: '/content/blog-posts',
            provider: BlogResourceProvider::class,
            order: ['created_at' => 'DESC'],
            parameters: [
                'active'          => new QueryParameter(key: 'active', property: 'active', filter: BooleanFilter::class, constraints: ['boolean']),
                'categoryId'      => new QueryParameter(key: 'categoryId', property: 'blog_post_category_id', filter: EqualsFilter::class, constraints: ['integer', 'min:1']),
                'slug'            => new QueryParameter(key: 'slug', property: 'slug', filter: new LocalizedEqualsFilter(), constraints: ['string', 'max:255']),
                'search'          => new QueryParameter(
                    key: 'search',
                    filter: new LocalizedSearchFilter(),
                    filterContext: ['properties' => ['name' => true, 'slug' => true]],
                    constraints: ['string', 'max:255'],
                ),
                'sort[position]'  => new QueryParameter(key: 'sort[position]', property: 'position', filter: OrderFilter::class),
                'sort[createdAt]' => new QueryParameter(key: 'sort[createdAt]', property: 'created_at', filter: OrderFilter::class),
            ],
        ),
    ],
    mcp: [
        'get_blog_post' => new McpTool(
            description: 'Get an active blog post with its category and media by identifier.',
            input: McpResourceIdentifierInput::class,
            provider: BlogResourceProvider::class,
        ),
        'list_blog_posts' => new McpToolCollection(
            description: 'List active blog posts with their categories and media.',
            input: McpCollectionInput::class,
            provider: BlogResourceProvider::class,
        ),
    ],
)]
final readonly class BlogPostResource
{
    /**
     * @param array<string, string> $title
     * @param array<string, string> $content
     * @param array<string, string> $slugs
     * @param array<int, MultimediaResource> $multimedia
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $title,
        public array $content,
        public array $slugs,
        public bool $active,
        public int $position,
        public BlogPostCategoryResource $section,
        public array $multimedia,
        public string $publishedAt,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
