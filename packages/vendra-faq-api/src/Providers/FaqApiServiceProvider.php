<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\Providers;

use Composer\InstalledVersions;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Misaf\VendraFaqApi\JsonApi\V1\Server as FaqServer;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class FaqApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-faq-api')
            ->hasRoute('api');
    }

    public function packageRegistered(): void
    {
        Config::set('jsonapi.servers.vendra-faq', Config::string('jsonapi.servers.vendra-faq', FaqServer::class));
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Faq API', fn() => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-faq-api')]);
    }
}
