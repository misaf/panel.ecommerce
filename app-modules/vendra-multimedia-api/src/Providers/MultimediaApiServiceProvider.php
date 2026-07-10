<?php

declare(strict_types=1);

namespace Misaf\VendraMultimediaApi\Providers;

use Illuminate\Foundation\Console\AboutCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class MultimediaApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-multimedia-api')
            ->hasRoute('api');
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Multimedia API', fn() => ['Version' => 'dev-master']);
    }
}
