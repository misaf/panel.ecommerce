<?php

declare(strict_types=1);

namespace Misaf\VendraMultimedia\Tests;

use Illuminate\Support\Facades\Http;
use Misaf\VendraMultimedia\Providers\MultimediaServiceProvider;
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
            MultimediaServiceProvider::class,
        ];
    }
}
