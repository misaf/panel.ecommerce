<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\Tests\Support;

use Illuminate\Support\ServiceProvider;
use Misaf\VendraAffiliateApi\JsonApi\V1\Server as AffiliateServer;

final class TestbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        config()->set('jsonapi.servers.vendra-affiliate', AffiliateServer::class);
    }
}
