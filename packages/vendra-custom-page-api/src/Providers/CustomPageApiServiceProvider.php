<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\Providers;

use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Misaf\VendraCustomPageApi\JsonApi\V1\Server as CustomPageServer;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class CustomPageApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-custom-page-api')
            ->hasRoute('api');
    }

    public function packageRegistered(): void
    {
        Config::set('jsonapi.servers.vendra-custom-page', Config::string('jsonapi.servers.vendra-custom-page', CustomPageServer::class));
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Custom Page API', fn(): array => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-custom-page-api')]);
    }
}
