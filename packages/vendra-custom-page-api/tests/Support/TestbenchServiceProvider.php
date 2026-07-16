<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\Tests\Support;

use Illuminate\Support\ServiceProvider;
use Misaf\VendraCustomPageApi\JsonApi\V1\Server as CustomPageServer;
use Misaf\VendraMultimediaApi\JsonApi\V1\Server as MultimediaServer;

final class TestbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        config()->set('jsonapi.servers.vendra-custom-page', CustomPageServer::class);
        config()->set('jsonapi.servers.vendra-multimedia', MultimediaServer::class);
    }
}
