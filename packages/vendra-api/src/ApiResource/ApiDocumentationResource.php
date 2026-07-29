<?php

declare(strict_types=1);

namespace Misaf\VendraApi\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\McpResource;
use Misaf\VendraApi\State\ApiDocumentationProvider;

#[ApiResource(
    operations: [],
    mcp: [
        'api_documentation' => new McpResource(
            uri: 'resource://vendra/api-documentation',
            name: 'vendra-api-documentation',
            description: 'Read the Vendra API identity, MCP endpoint, and enabled serialization formats.',
            mimeType: 'application/json',
            provider: ApiDocumentationProvider::class,
        ),
    ],
)]
final readonly class ApiDocumentationResource
{
    /**
     * @param array<int, string> $formats
     */
    public function __construct(
        public string $title,
        public string $description,
        public string $version,
        public string $mcpEndpoint,
        public array $formats,
    ) {}
}
