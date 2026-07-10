<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\Tests\Support;

use Illuminate\Support\ServiceProvider;
use Misaf\VendraBlogApi\JsonApi\V1\Server as BlogServer;
use Misaf\VendraMultimediaApi\JsonApi\V1\Server as MultimediaServer;

final class TestbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        config()->set('jsonapi.servers.vendra-blog', BlogServer::class);
        config()->set('jsonapi.servers.vendra-multimedia', MultimediaServer::class);
    }
}
