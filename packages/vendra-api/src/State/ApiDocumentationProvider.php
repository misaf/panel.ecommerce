<?php

declare(strict_types=1);

namespace Misaf\VendraApi\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Illuminate\Contracts\Config\Repository;
use JsonException;
use Mcp\Schema\Content\TextResourceContents;
use Mcp\Schema\Result\ReadResourceResult;

/**
 * @implements ProviderInterface<ReadResourceResult>
 */
final readonly class ApiDocumentationProvider implements ProviderInterface
{
    public function __construct(private Repository $config) {}

    /**
     * @throws JsonException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ReadResourceResult
    {
        $formats = $this->config->get('api-platform.formats', []);

        $documentation = json_encode([
            'title'       => (string) $this->config->get('api-platform.title', 'Vendra API'),
            'description' => (string) $this->config->get('api-platform.description', ''),
            'version'     => (string) $this->config->get('api-platform.version', '1.0.0'),
            'mcpEndpoint' => '/mcp',
            'formats'     => is_array($formats) ? array_keys($formats) : [],
        ], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);

        return new ReadResourceResult([
            new TextResourceContents(
                uri: 'resource://vendra/api-documentation',
                mimeType: 'application/json',
                text: $documentation,
            ),
        ]);
    }
}
