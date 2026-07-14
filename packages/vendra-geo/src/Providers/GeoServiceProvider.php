<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Providers;

use Composer\InstalledVersions;

use Filament\Panel;
use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraGeo\Console\Commands\SeedCommand;
use Misaf\VendraGeo\GeoPlugin;
use Misaf\VendraSupport\Filament\Concerns\ResolvesConfiguredPanels;
use Misaf\VendraSupport\Support\TenantSeeders;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class GeoServiceProvider extends PackageServiceProvider
{
    use ResolvesConfiguredPanels;

    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-geo')
            ->hasTranslations()
            ->hasMigrations([
                'create_geo_tables',
            ])
            ->hasCommands(SeedCommand::class)
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-geo');
            });
    }

    public function packageRegistered(): void
    {
        Panel::configureUsing(function (Panel $panel): void {
            if ( ! $this->shouldRegisterOnPanel($panel->getId(), 'vendra-geo')) {
                return;
            }

            $panel->plugin(GeoPlugin::make());
        });
    }

    public function packageBooted(): void
    {
        $this->app->make(TenantSeeders::class)->register('vendra-geo:seed', priority: 45);

        AboutCommand::add('Vendra Geo', fn(): array => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-geo')]);
    }
}
