<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\Providers;

use ApiPlatform\State\ProviderInterface;
use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Misaf\VendraProductApi\State\ProductResourceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class ProductApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-product-api');
    }

    public function packageRegistered(): void
    {
        Config::set('api-platform.resources', [
            ...Config::array('api-platform.resources', []),
            dirname(__DIR__) . '/ApiResource',
        ]);

        $this->app->tag(ProductResourceProvider::class, ProviderInterface::class);
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Product API', fn(): array => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-product-api')]);
    }
}
