<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\Providers;

use Composer\InstalledVersions;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Misaf\VendraCartApi\JsonApi\V1\Server as CartServer;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class CartApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-cart-api')
            ->hasRoute('api');
    }

    public function packageRegistered(): void
    {
        Config::set('jsonapi.servers.vendra-cart', Config::string('jsonapi.servers.vendra-cart', CartServer::class));
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Cart API', fn(): array => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-cart-api')]);
    }
}
