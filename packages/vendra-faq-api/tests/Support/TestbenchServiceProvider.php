<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\Tests\Support;

use Illuminate\Support\ServiceProvider;
use Misaf\VendraFaqApi\JsonApi\V1\Server as FaqServer;
use Misaf\VendraMultimediaApi\JsonApi\V1\Server as MultimediaServer;

final class TestbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        config()->set('jsonapi.servers.vendra-faq', FaqServer::class);
        config()->set('jsonapi.servers.vendra-multimedia', MultimediaServer::class);
    }
}
