<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\Providers;

use Composer\InstalledVersions;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
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
        Config::set('jsonapi.servers.vendra-product', Config::string('jsonapi.servers.vendra-product', ProductServer::class));
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Product API', fn(): array => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-product-api')]);
    }
}
