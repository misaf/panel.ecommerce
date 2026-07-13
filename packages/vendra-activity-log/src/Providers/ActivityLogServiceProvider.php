<?php

declare(strict_types=1);

namespace Misaf\VendraActivityLog\Providers;

use Filament\Panel;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Event;
use Misaf\VendraActivityLog\ActivityLogPlugin;
use Misaf\VendraActivityLog\Console\Commands\SeedCommand;
use Misaf\VendraActivityLog\Listeners\LogModelActivity;
use Misaf\VendraSupport\Filament\Concerns\ResolvesConfiguredPanels;
use Misaf\VendraSupport\Support\TenantSeeders;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class ActivityLogServiceProvider extends PackageServiceProvider
{
    use ResolvesConfiguredPanels;

    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-activity-log')
            ->hasTranslations()
            ->hasMigrations([
                'add_tenant_id_column_to_activity_log_table',
            ])
            ->hasCommands(SeedCommand::class)
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-activity-log');
            });
    }

    public function packageRegistered(): void
    {
        Panel::configureUsing(function (Panel $panel): void {
            if ( ! $this->shouldRegisterOnPanel($panel->getId(), 'vendra-activity-log')) {
                return;
            }

            $panel->plugin(ActivityLogPlugin::make());
        });
    }

    public function packageBooted(): void
    {
        $this->app->make(TenantSeeders::class)->register('vendra-activity-log:seed', priority: 85);

        AboutCommand::add('Vendra Activity Log', fn() => ['Version' => 'dev-master']);

        $this->registerActivityLogListeners();
    }

    /**
     * Bind the activity logger to the wildcard Eloquent lifecycle events so any
     * model implementing ShouldLogActivity is logged without depending on this
     * package. Absence of this provider means no listeners and no logging.
     */
    private function registerActivityLogListeners(): void
    {
        foreach (['created', 'updated', 'deleted', 'restored'] as $event) {
            Event::listen("eloquent.{$event}: *", LogModelActivity::class);
        }
    }
}
