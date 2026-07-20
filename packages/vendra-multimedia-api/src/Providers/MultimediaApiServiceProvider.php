<?php

declare(strict_types=1);

namespace Misaf\VendraMultimediaApi\Providers;

use Composer\InstalledVersions;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Misaf\VendraMultimediaApi\JsonApi\V1\Server as MultimediaServer;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class MultimediaApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-multimedia-api')
            ->hasRoute('api');
    }

    public function packageRegistered(): void
    {
        Config::set('jsonapi.servers.vendra-multimedia', Config::string('jsonapi.servers.vendra-multimedia', MultimediaServer::class));
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Multimedia API', fn(): array => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-multimedia-api')]);
    }
}
