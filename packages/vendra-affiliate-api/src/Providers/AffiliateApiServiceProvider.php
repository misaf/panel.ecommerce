<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\Providers;

use Composer\InstalledVersions;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Misaf\VendraAffiliateApi\JsonApi\V1\Server as AffiliateServer;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class AffiliateApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-affiliate-api')
            ->hasRoute('api');
    }

    public function packageRegistered(): void
    {
        Config::set('jsonapi.servers.vendra-affiliate', Config::string('jsonapi.servers.vendra-affiliate', AffiliateServer::class));
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Affiliate API', fn(): array => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-affiliate-api')]);
    }
}
