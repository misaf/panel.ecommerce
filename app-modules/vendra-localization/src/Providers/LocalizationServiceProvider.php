<?php

declare(strict_types=1);

namespace Misaf\VendraLocalization\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Misaf\VendraLocalization\Contracts\LocaleResolver;
use Misaf\VendraLocalization\Http\Middleware\SetLocale;
use Misaf\VendraLocalization\Resolvers\ChainLocaleResolver;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class LocalizationServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-localization')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->bind(LocaleResolver::class, fn(Application $app): LocaleResolver => ChainLocaleResolver::fromSources(
            $app,
            Config::array('vendra-localization.resolvers'),
        ));
    }

    public function packageBooted(): void
    {
        $this->app->make(Router::class)->aliasMiddleware('vendra.locale', SetLocale::class);
    }
}
