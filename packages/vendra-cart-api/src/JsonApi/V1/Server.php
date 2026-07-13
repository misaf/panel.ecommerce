<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\JsonApi\V1;

use LaravelJsonApi\Core\Server\Server as BaseServer;
use Misaf\VendraCartApi\JsonApi\V1\CartItems\CartItemSchema;
use Misaf\VendraCartApi\JsonApi\V1\Carts\CartSchema;

final class Server extends BaseServer
{
    protected string $baseUri = '/v1';

    public function authorizable(): bool
    {
        return false;
    }

    /**
     * @return array<int, class-string>
     */
    public function allSchemas(): array
    {
        return [
            CartSchema::class,
            CartItemSchema::class,
        ];
    }
}
