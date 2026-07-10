<?php

declare(strict_types=1);

namespace Misaf\VendraActivityLog\Tests;

use Illuminate\Support\Facades\Http;
use Misaf\VendraActivityLog\Providers\ActivityLogServiceProvider;
use Misaf\VendraSupport\Providers\SupportServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Override;
use Spatie\Activitylog\ActivitylogServiceProvider as SpatieActivitylogServiceProvider;
use Spatie\Activitylog\Models\Activity;

abstract class TestCase extends OrchestraTestCase
{
    #[Override]
    protected function defineDatabaseMigrations(): void
    {
        $this->runActivityLogPackageMigrations();

        // Fixture-only table backing the test widget models.
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    /**
     * Build the real activity_log schema from the migrations the packages ship,
     * rather than duplicating it here: the base table and its columns come from
     * spatie/laravel-activitylog, and the tenant_id column comes from this
     * module's own migration (a no-op unless a TenantResolver is bound).
     */
    private function runActivityLogPackageMigrations(): void
    {
        $spatiePath = __DIR__ . '/../vendor/spatie/laravel-activitylog/database/migrations';

        /** @var array<string, class-string> $spatieMigrations */
        $spatieMigrations = [
            'create_activity_log_table'                   => 'CreateActivityLogTable',
            'add_event_column_to_activity_log_table'      => 'AddEventColumnToActivityLogTable',
            'add_batch_uuid_column_to_activity_log_table' => 'AddBatchUuidColumnToActivityLogTable',
        ];

        foreach ($spatieMigrations as $file => $class) {
            include_once "{$spatiePath}/{$file}.php.stub";

            (new $class())->up();
        }

        (include __DIR__ . '/../database/migrations/add_tenant_id_column_to_activity_log_table.php.stub')->up();
    }

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    #[Override]
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('activitylog.enabled', true);
        $app['config']->set('activitylog.activity_model', Activity::class);
        $app['config']->set('activitylog.table_name', 'activity_log');
        $app['config']->set('activitylog.default_log_name', 'default');
    }

    protected function getPackageProviders($app): array
    {
        return [
            SupportServiceProvider::class,
            SpatieActivitylogServiceProvider::class,
            ActivityLogServiceProvider::class,
        ];
    }
}
