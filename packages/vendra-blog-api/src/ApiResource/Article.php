<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraBlogApi\State\BlogResourceProvider;

#[ApiResource(
    shortName: 'BlogPost',
    operations: [
        new Get(uriTemplate: '/content/blog-posts/{id}', provider: BlogResourceProvider::class),
        new GetCollection(
            uriTemplate: '/content/blog-posts',
            provider: BlogResourceProvider::class,
            parameters: [
                'active' => new QueryParameter(key: 'active', property: 'active', filter: BooleanFilter::class, constraints: ['boolean']),
            ],
        ),
    ],
)]
final readonly class Article
{
    /**
     * @param array<string, string> $title
     * @param array<string, string> $content
     * @param array<string, string> $slugs
     * @param array<int, ResourceReference> $multimedia
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $title,
        public array $content,
        public array $slugs,
        public bool $active,
        public ResourceReference $section,
        public array $multimedia,
        public string $publishedAt,
    ) {}
}
