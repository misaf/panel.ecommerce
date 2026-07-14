<?php

declare(strict_types=1);

namespace Misaf\VendraSocialite\Providers;

use Composer\InstalledVersions;

use Filament\Panel;
use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraSocialite\Support\SocialiteRegistrar;
use Misaf\VendraSupport\Filament\Concerns\ResolvesConfiguredPanels;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class SocialiteServiceProvider extends PackageServiceProvider
{
    use ResolvesConfiguredPanels;

    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-socialite')
            ->hasConfigFile()
            ->hasMigration('create_socialite_users_table')
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-socialite');
            });
    }

    public function packageRegistered(): void
    {
        Panel::configureUsing(function (Panel $panel): void {
            if ( ! $this->shouldRegisterOnPanel($panel->getId(), 'vendra-socialite')) {
                return;
            }

            $panel->plugin(SocialiteRegistrar::make());
        });
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Socialite', fn() => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-socialite')]);
    }
}
