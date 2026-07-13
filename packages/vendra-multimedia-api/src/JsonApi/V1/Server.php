<?php

declare(strict_types=1);

namespace Misaf\VendraMultimediaApi\JsonApi\V1;

use LaravelJsonApi\Core\Server\Server as BaseServer;
use Misaf\VendraMultimediaApi\JsonApi\V1\Multimedia\MultimediaSchema;

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
            MultimediaSchema::class,
        ];
    }
}
