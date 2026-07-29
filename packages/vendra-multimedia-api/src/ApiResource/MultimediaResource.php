<?php

declare(strict_types=1);

namespace Misaf\VendraMultimediaApi\ApiResource;

use ApiPlatform\Laravel\Eloquent\Filter\EqualsFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\McpTool;
use ApiPlatform\Metadata\McpToolCollection;
use ApiPlatform\Metadata\QueryParameter;
use Misaf\VendraApi\ApiResource\McpCollectionInput;
use Misaf\VendraApi\ApiResource\McpResourceIdentifierInput;
use Misaf\VendraMultimediaApi\State\MultimediaResourceProvider;

#[ApiResource(
    shortName: 'Multimedia',
    operations: [
        new Get(uriTemplate: '/content/multimedia/{id}', provider: MultimediaResourceProvider::class),
        new GetCollection(
            uriTemplate: '/content/multimedia',
            provider: MultimediaResourceProvider::class,
            parameters: [
                'mimeType' => new QueryParameter(key: 'mimeType', property: 'mime_type', filter: EqualsFilter::class, constraints: ['string', 'max:255']),
            ],
        ),
    ],
    mcp: [
        'get_multimedia' => new McpTool(
            description: 'Get a public multimedia asset and its conversions by identifier.',
            input: McpResourceIdentifierInput::class,
            provider: MultimediaResourceProvider::class,
        ),
        'list_multimedia' => new McpToolCollection(
            description: 'List public multimedia assets and their conversions.',
            input: McpCollectionInput::class,
            provider: MultimediaResourceProvider::class,
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
