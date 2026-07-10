<?php

declare(strict_types=1);

namespace Misaf\VendraApi\Tests;

use Illuminate\Support\Facades\Http;
use Misaf\VendraApi\Providers\ApiServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Override;

abstract class TestCase extends OrchestraTestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    #[Override]
    protected function getPackageProviders($app): array
    {
        return [
            ApiServiceProvider::class,
        ];
    }
}
