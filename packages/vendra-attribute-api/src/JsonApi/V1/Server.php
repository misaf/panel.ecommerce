<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\JsonApi\V1;

use LaravelJsonApi\Core\Server\Server as BaseServer;
use Misaf\VendraAttributeApi\JsonApi\V1\Attributes\AttributeSchema;
use Misaf\VendraAttributeApi\JsonApi\V1\AttributeValues\AttributeValueSchema;

final class Server extends BaseServer
{
    protected string $baseUri = '/v1';

    public function authorizable(): bool
    {
        return false;
    }

    /**
     * @return list<class-string>
     */
    public function allSchemas(): array
    {
        return [
            AttributeSchema::class,
            AttributeValueSchema::class,
        ];
    }
}
