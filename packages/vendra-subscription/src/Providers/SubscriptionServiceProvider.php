<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Providers;

use Composer\InstalledVersions;

use Illuminate\Foundation\Console\AboutCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class SubscriptionServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-subscription')
            ->hasMigrations([
                'create_plans_table',
                'create_subscriptions_table',
            ])
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-subscription');
            });
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Subscription', fn(): array => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-subscription')]);
    }
}
