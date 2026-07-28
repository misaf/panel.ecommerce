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
    shortName: 'Faq',
    operations: [
        new Get(uriTemplate: '/content/faqs/{id}', provider: HelpResourceProvider::class),
        new GetCollection(uriTemplate: '/content/faqs', provider: HelpResourceProvider::class),
    ],
)]
final readonly class FaqResource
{
    /**
     * @param array<string, string> $question
     * @param array<string, string> $answer
     * @param array<int, ResourceReference> $multimedia
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $question,
        public array $answer,
        public ResourceReference $topic,
        public array $multimedia,
    ) {}
}
