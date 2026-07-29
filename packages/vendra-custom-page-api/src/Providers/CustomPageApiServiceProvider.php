<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\Providers;

use ApiPlatform\State\ProviderInterface;
use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Misaf\VendraCustomPageApi\State\CustomPageCategoryResourceProvider;
use Misaf\VendraCustomPageApi\State\CustomPageResourceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class CustomPageApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-custom-page-api');
    }

    public function packageRegistered(): void
    {
        Config::set('api-platform.resources', [
            ...Config::array('api-platform.resources', []),
            dirname(__DIR__) . '/ApiResource',
        ]);

        $this->app->tag([
            CustomPageResourceProvider::class,
            CustomPageCategoryResourceProvider::class,
        ], ProviderInterface::class);
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Custom Page API', fn(): array => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-custom-page-api')]);
    }
}
