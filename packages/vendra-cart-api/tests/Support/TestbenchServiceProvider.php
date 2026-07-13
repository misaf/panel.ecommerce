<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\Tests\Support;

use Illuminate\Support\ServiceProvider;
use Misaf\VendraCartApi\JsonApi\V1\Server as CartServer;

final class TestbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        config()->set('jsonapi.servers.vendra-cart', CartServer::class);
    }
}
