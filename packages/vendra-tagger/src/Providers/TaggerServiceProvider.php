<?php

declare(strict_types=1);

namespace Misaf\VendraTagger\Providers;

use Filament\Panel;
use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraSupport\Filament\Concerns\ResolvesConfiguredPanels;
use Misaf\VendraSupport\Support\TenantSeeders;
use Misaf\VendraTagger\Console\Commands\SeedCommand;
use Misaf\VendraTagger\TaggerPlugin;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class TaggerServiceProvider extends PackageServiceProvider
{
    use ResolvesConfiguredPanels;

    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-tagger')
            ->hasTranslations()
            ->hasMigrations([
                'add_tenant_id_column_to_tags_table',
                'rename_order_column_to_position_on_tags_table',
                'add_index_to_tags_position_column',
            ])
            ->hasCommands(SeedCommand::class)
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-tagger');
            });
    }

    public function packageRegistered(): void
    {
        Panel::configureUsing(function (Panel $panel): void {
            if ( ! $this->shouldRegisterOnPanel($panel->getId(), 'vendra-tagger')) {
                return;
            }

            $panel->plugin(TaggerPlugin::make());
        });
    }

    public function packageBooted(): void
    {
        $this->app->make(TenantSeeders::class)->register('vendra-tagger:seed', priority: 70);

        AboutCommand::add('Vendra Tagger', fn() => ['Version' => 'dev-master']);
    }
}
