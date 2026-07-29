<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraFaqApi\State\HelpResourceProvider;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;

#[ApiResource(
    shortName: 'FaqCategory',
    operations: [
        new Get(uriTemplate: '/content/faq-categories/{id}', provider: HelpResourceProvider::class),
        new GetCollection(uriTemplate: '/content/faq-categories', provider: HelpResourceProvider::class),
    ],
)]
final readonly class FaqCategoryResource
{
    /**
     * @param array<string, string> $title
     * @param array<string, string> $slugs
     * @param array<string, string> $description
     * @param array<int, ResourceReference> $faqs
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
        public array $faqs,
        public array $multimedia,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
