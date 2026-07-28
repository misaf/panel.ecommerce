<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraCustomPageApi\State\ContentResourceProvider;

#[ApiResource(
    shortName: 'CustomPageCategory',
    operations: [
        new Get(uriTemplate: '/content/custom-page-categories/{id}', provider: ContentResourceProvider::class),
        new GetCollection(uriTemplate: '/content/custom-page-categories', provider: ContentResourceProvider::class),
    ],
)]
final readonly class ContentSection
{
    /**
     * @param array<string, string> $title
     * @param array<int, ResourceReference> $pages
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $title,
        public array $pages,
    ) {}
}
