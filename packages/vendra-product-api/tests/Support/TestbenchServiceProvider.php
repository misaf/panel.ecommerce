<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\Tests\Support;

use Illuminate\Support\ServiceProvider;
use Misaf\VendraMultimediaApi\JsonApi\V1\Server as MultimediaServer;
use Misaf\VendraProductApi\JsonApi\V1\Server as ProductServer;

final class TestbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        config()->set('jsonapi.servers.vendra-product', ProductServer::class);
        config()->set('jsonapi.servers.vendra-multimedia', MultimediaServer::class);
    }
}
