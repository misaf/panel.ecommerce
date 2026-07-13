<?php

declare(strict_types=1);

namespace Misaf\VendraApi\Providers;

use Illuminate\Foundation\Console\AboutCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class ApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-api');
    }

    public function packageBooted(): void
    {
        if ( ! $this->app->runningInConsole()) {
            return;
        }

        AboutCommand::add('Vendra API', fn() => ['Version' => 'dev-master']);
    }
}
