<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\BooleanFilter;
use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Laravel\Eloquent\Filter\OrderFilter;
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
use Misaf\VendraApi\Eloquent\Filter\LocalizedEqualsFilter;
use Misaf\VendraApi\Eloquent\Filter\LocalizedSearchFilter;
use Misaf\VendraBlog\Models\BlogPost;
use Misaf\VendraBlogApi\State\BlogPostResourceProvider;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;

#[ApiResource(
    shortName: 'BlogPost',
    stateOptions: new Options(modelClass: BlogPost::class, handleLinks: BlogPostResourceProvider::class),
    mcp: [
        'get_blog_post' => new McpTool(
            description: 'Get an active blog post with its category and media by identifier.',
            input: McpResourceIdentifierInput::class,
            provider: BlogPostResourceProvider::class,
        ),
        'list_blog_posts' => new McpToolCollection(
            description: 'List active blog posts with their categories and media.',
            input: McpCollectionInput::class,
            provider: BlogPostResourceProvider::class,
        ),
    ],
)]
#[Get(uriTemplate: '/content/blog-posts/{id}', provider: BlogPostResourceProvider::class)]
#[GetCollection(
    uriTemplate: '/content/blog-posts',
    provider: BlogPostResourceProvider::class,
    order: ['created_at' => 'DESC'],
    parameters: [
        'active'          => new QueryParameter(key: 'active', property: 'active', filter: BooleanFilter::class, constraints: ['boolean']),
        'categoryId'      => new QueryParameter(key: 'categoryId', property: 'blog_post_category_id', filter: EqualsFilter::class, constraints: ['integer', 'min:1']),
        'slug'            => new QueryParameter(key: 'slug', property: 'slug', filter: LocalizedEqualsFilter::class, constraints: ['string', 'max:255']),
        'search'          => new QueryParameter(
            key: 'search',
            filter: LocalizedSearchFilter::class,
            filterContext: ['properties' => ['name' => true, 'slug' => true]],
            constraints: ['string', 'max:255'],
        ),
        'sort[position]'  => new QueryParameter(key: 'sort[position]', property: 'position', filter: OrderFilter::class),
        'sort[createdAt]' => new QueryParameter(key: 'sort[createdAt]', property: 'created_at', filter: OrderFilter::class),
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
        public ResourceReference $blogPostCategory,
        public array $multimedia,
        public string $publishedAt,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
