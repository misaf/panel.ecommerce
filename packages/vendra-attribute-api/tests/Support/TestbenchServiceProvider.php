<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\Tests\Support;

use Illuminate\Support\ServiceProvider;
use Misaf\VendraAttributeApi\JsonApi\V1\Server as AttributeServer;

final class TestbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        config()->set('jsonapi.servers.vendra-attribute', AttributeServer::class);
    }
}
