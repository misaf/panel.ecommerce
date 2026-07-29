<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\ApiResource;

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
use Misaf\VendraFaqApi\State\HelpResourceProvider;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;

#[ApiResource(
    shortName: 'Faq',
    operations: [
        new Get(uriTemplate: '/content/faqs/{id}', provider: HelpResourceProvider::class),
        new GetCollection(
            uriTemplate: '/content/faqs',
            provider: HelpResourceProvider::class,
            order: ['position' => 'ASC'],
            parameters: [
                'categoryId'      => new QueryParameter(key: 'categoryId', property: 'faq_category_id', filter: EqualsFilter::class, constraints: ['integer', 'min:1']),
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
        'get_faq' => new McpTool(
            description: 'Get an active FAQ with its category and media by identifier.',
            input: McpResourceIdentifierInput::class,
            provider: HelpResourceProvider::class,
        ),
        'list_faqs' => new McpToolCollection(
            description: 'List active FAQs with their categories and media.',
            input: McpCollectionInput::class,
            provider: HelpResourceProvider::class,
        ),
    ],
)]
final readonly class FaqResource
{
    /**
     * @param array<string, string> $question
     * @param array<string, string> $answer
     * @param array<string, string> $slugs
     * @param array<int, MultimediaResource> $multimedia
     */
    public function __construct(
        #[ApiProperty(identifier: true)]
        public int $id,
        public array $question,
        public array $answer,
        public array $slugs,
        public int $position,
        public bool $active,
        public FaqCategoryResource $topic,
        public array $multimedia,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
