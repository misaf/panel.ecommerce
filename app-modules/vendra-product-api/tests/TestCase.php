<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\Tests;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Http;
use LaravelJsonApi\Encoder\Neomerx\ServiceProvider as LaravelJsonApiEncoderServiceProvider;
use LaravelJsonApi\Laravel\ServiceProvider as LaravelJsonApiServiceProvider;
use Misaf\VendraApi\Providers\ApiServiceProvider;
use Misaf\VendraMultimediaApi\JsonApi\V1\Server as VendraMultimediaServer;
use Misaf\VendraMultimediaApi\Providers\MultimediaApiServiceProvider;
use Misaf\VendraProductApi\JsonApi\V1\Server as VendraProductServer;
use Misaf\VendraProductApi\Providers\ProductApiServiceProvider;
use Misaf\VendraSupport\Providers\SupportServiceProvider;
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
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('activitylog', [
            'enabled'                             => false,
            'delete_records_older_than_days'      => 365,
            'default_log_name'                    => 'default',
            'default_auth_driver'                 => null,
            'subject_returns_soft_deleted_models' => false,
            'activity_model'                      => \Spatie\Activitylog\Models\Activity::class,
            'table_name'                          => 'activity_log',
            'database_connection'                 => null,
        ]);
        $app['config']->set('eloquent-sortable.order_column_name', 'position');
        $app['config']->set('jsonapi.namespace', 'JsonApi');
        $app['config']->set('jsonapi.servers.vendra-multimedia', VendraMultimediaServer::class);
        $app['config']->set('jsonapi.servers.vendra-product', VendraProductServer::class);
    }

    #[Override]
    protected function defineRoutes($router): void
    {
        if ( ! $router instanceof Router) {
            return;
        }

        require dirname(__DIR__) . '/routes/api.php';
    }

    #[Override]
    protected function getPackageProviders($app): array
    {
        return [
            LaravelJsonApiEncoderServiceProvider::class,
            LaravelJsonApiServiceProvider::class,
            ApiServiceProvider::class,
            SupportServiceProvider::class,
            MultimediaApiServiceProvider::class,
            ProductApiServiceProvider::class,
        ];
    }
}
