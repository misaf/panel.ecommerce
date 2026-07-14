<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\Providers;

use Illuminate\Foundation\Console\AboutCommand;
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
        config()->set('jsonapi.servers.vendra-affiliate', config('jsonapi.servers.vendra-affiliate', AffiliateServer::class));
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Affiliate API', fn() => ['Version' => 'dev-master']);
    }
}
