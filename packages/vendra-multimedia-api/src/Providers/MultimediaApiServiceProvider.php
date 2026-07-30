<?php

declare(strict_types=1);

namespace Misaf\VendraMultimediaApi\Providers;

use ApiPlatform\Laravel\Eloquent\State\LinksHandlerInterface;
use ApiPlatform\State\ProviderInterface;
use Composer\InstalledVersions;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Misaf\VendraMultimediaApi\State\MultimediaResourceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class MultimediaApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-multimedia-api');
    }

    public function packageRegistered(): void
    {
        Config::set('api-platform.resources', [
            ...Config::array('api-platform.resources', []),
            dirname(__DIR__) . '/ApiResource',
        ]);

        $this->app->tag(MultimediaResourceProvider::class, [LinksHandlerInterface::class, ProviderInterface::class]);
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Multimedia API', fn(): array => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-multimedia-api')]);
    }
}
