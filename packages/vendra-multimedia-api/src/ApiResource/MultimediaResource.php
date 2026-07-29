<?php

declare(strict_types=1);

namespace Misaf\VendraMultimediaApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use Misaf\VendraMultimediaApi\State\AssetProvider;

#[ApiResource(
    shortName: 'Multimedia',
    operations: [
        new Get(uriTemplate: '/content/multimedia/{id}', provider: AssetProvider::class),
        new GetCollection(
            uriTemplate: '/content/multimedia',
            provider: AssetProvider::class,
            parameters: [
                'mimeType' => new QueryParameter(key: 'mimeType', property: 'mime_type', filter: EqualsFilter::class, constraints: ['string', 'max:255']),
            ],
        ),
    ],
)]
final readonly class MultimediaResource
{
    /**
     * @param array<string, mixed> $generatedConversions
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public string $uuid,
        public string $name,
        public string $fileName,
        public string $collection,
        public ?string $mimeType,
        public int $bytes,
        public ?string $url,
        public array $generatedConversions,
    ) {}
}
