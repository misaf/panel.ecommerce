<?php

declare(strict_types=1);

namespace Misaf\VendraApi\Providers;

use ApiPlatform\State\ProviderInterface;
use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Misaf\VendraApi\State\ApiDocumentationProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class ApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-api');
    }

    public function packageRegistered(): void
    {
        $this->app->tag(ApiDocumentationProvider::class, ProviderInterface::class);

        Config::set('api-platform.resources', [
            ...Config::array('api-platform.resources', []),
            dirname(__DIR__) . '/ApiResource',
        ]);
    }

    public function packageBooted(): void
    {
        if ( ! $this->app->runningInConsole()) {
            return;
        }

        AboutCommand::add('Vendra API', fn(): array => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-api')]);
    }
}
