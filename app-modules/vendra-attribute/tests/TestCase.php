<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Tests;

use Misaf\VendraAttribute\Providers\AttributeServiceProvider;
use Misaf\VendraSupport\Providers\SupportServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function defineDatabaseMigrations(): void
    {
        $migration = require dirname(__DIR__) . '/database/migrations/create_attributes_table.php.stub';

        $migration->up();
    }

    protected function getPackageProviders($app): array
    {
        return [
            SupportServiceProvider::class,
            AttributeServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
