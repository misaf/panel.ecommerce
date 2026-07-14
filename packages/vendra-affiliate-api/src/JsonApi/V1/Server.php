<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\JsonApi\V1;

use LaravelJsonApi\Core\Server\Server as BaseServer;
use Misaf\VendraAffiliateApi\JsonApi\V1\Affiliates\AffiliateSchema;

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
            AffiliateSchema::class,
        ];
    }
}
