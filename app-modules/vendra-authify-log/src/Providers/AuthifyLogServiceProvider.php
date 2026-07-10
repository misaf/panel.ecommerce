<?php

declare(strict_types=1);

namespace Misaf\VendraAuthifyLog\Providers;

use Filament\Panel;
use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraAuthifyLog\AuthifyLogPlugin;
use Misaf\VendraAuthifyLog\Console\Commands\AuthifyLogChannelCommand;
use Misaf\VendraAuthifyLog\Console\Commands\SeedCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class AuthifyLogServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-authify-log')
            ->hasTranslations()
            ->hasConfigFile()
            ->hasMigrations([
                'create_authify_logs_table',
            ])
            ->hasCommands(
                AuthifyLogChannelCommand::class,
                SeedCommand::class,
            )
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-authify-log');
            });
    }

    public function packageRegistered(): void
    {
        Panel::configureUsing(function (Panel $panel): void {
            if ('admin' !== $panel->getId()) {
                return;
            }

            $panel->plugin(AuthifyLogPlugin::make());
        });
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Authify Log', fn() => ['Version' => 'dev-master']);
    }
}
