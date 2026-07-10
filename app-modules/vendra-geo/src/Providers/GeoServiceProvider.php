<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Providers;

use Filament\Panel;
use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraGeo\Console\Commands\SeedCommand;
use Misaf\VendraGeo\GeoPlugin;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class GeoServiceProvider extends PackageServiceProvider
{
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
            if ('admin' !== $panel->getId()) {
                return;
            }

            $panel->plugin(GeoPlugin::make());
        });
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Geo', fn(): array => ['Version' => 'dev-master']);
    }
}
