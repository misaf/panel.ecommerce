<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\Providers;

use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraMultimediaApi\JsonApi\V1\Server as MultimediaServer;
use Misaf\VendraProductApi\JsonApi\V1\Server as ProductServer;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class ProductApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-product-api')
            ->hasRoute('api');
    }

    public function packageRegistered(): void
    {
        config()->set('jsonapi.servers.vendra-product', config('jsonapi.servers.vendra-product', ProductServer::class));
        config()->set('jsonapi.servers.vendra-multimedia', config('jsonapi.servers.vendra-multimedia', MultimediaServer::class));
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Product API', fn() => ['Version' => 'dev-master']);
    }
}
