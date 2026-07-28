<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraBlogApi\State\BlogResourceProvider;

#[ApiResource(
    shortName: 'BlogPostCategory',
    operations: [
        new Get(uriTemplate: '/content/blog-post-categories/{id}', provider: BlogResourceProvider::class),
        new GetCollection(uriTemplate: '/content/blog-post-categories', provider: BlogResourceProvider::class),
    ],
)]
final readonly class BlogPostCategoryResource
{
    /**
     * @param array<string, string> $title
     * @param array<string, string> $slugs
     * @param array<int, ResourceReference> $blogPosts
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $title,
        public array $slugs,
        public array $blogPosts,
    ) {}
}
