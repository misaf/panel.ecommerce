<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Providers;

use Filament\Panel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraCart\CartPlugin;
use Misaf\VendraCart\Console\Commands\PruneExpiredCartsCommand;
use Misaf\VendraCart\Console\Commands\SeedCommand;
use Misaf\VendraSupport\Filament\Concerns\ResolvesConfiguredPanels;
use Misaf\VendraSupport\Support\TenantSeeders;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class CartServiceProvider extends PackageServiceProvider
{
    use ResolvesConfiguredPanels;

    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-cart')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigrations([
                'create_carts_table',
            ])
            ->hasCommands(SeedCommand::class, PruneExpiredCartsCommand::class)
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-cart');
            });
    }

    public function packageRegistered(): void
    {
        Panel::configureUsing(function (Panel $panel): void {
            if ( ! $this->shouldRegisterOnPanel($panel->getId(), CartPlugin::ID)) {
                return;
            }

            $panel->plugin(CartPlugin::make());
        });
    }

    public function packageBooted(): void
    {
        $this->app->make(TenantSeeders::class)->register('vendra-cart:seed', priority: 58);

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule): void {
            $schedule->command(PruneExpiredCartsCommand::class)->daily();
        });

        AboutCommand::add('Vendra Cart', fn(): array => ['Version' => 'dev-master']);
    }
}
