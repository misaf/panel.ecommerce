<?php

declare(strict_types=1);

namespace Misaf\VendraMultimediaApi\Providers;

use Illuminate\Foundation\Console\AboutCommand;
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
        config()->set('jsonapi.servers.vendra-multimedia', config('jsonapi.servers.vendra-multimedia', MultimediaServer::class));
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Multimedia API', fn() => ['Version' => 'dev-master']);
    }
}
