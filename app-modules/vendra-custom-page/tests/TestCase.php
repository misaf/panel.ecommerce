<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Tests;

use Illuminate\Support\Facades\Http;
use Misaf\VendraCustomPage\Providers\CustomPageServiceProvider;
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

    protected function getPackageProviders($app): array
    {
        return [
            CustomPageServiceProvider::class,
        ];
    }
}
