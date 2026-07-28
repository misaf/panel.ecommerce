<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraFaqApi\State\HelpResourceProvider;

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
     * @param array<int, ResourceReference> $faqs
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $title,
        public array $faqs,
    ) {}
}
