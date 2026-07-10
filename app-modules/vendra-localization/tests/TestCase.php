<?php

declare(strict_types=1);

namespace Misaf\VendraLocalization\Tests;

use Misaf\VendraLocalization\Providers\LocalizationServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Override;

abstract class TestCase extends OrchestraTestCase
{
    #[Override]
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.locale', 'en');
        $app['config']->set('app.fallback_locale', 'en');
        $app['config']->set('vendra-localization.supported_locales', ['en', 'de', 'fa', 'fr']);
    }

    /**
     * @return array<int, class-string>
     */
    #[Override]
    protected function getPackageProviders($app): array
    {
        return [
            LocalizationServiceProvider::class,
        ];
    }
}
