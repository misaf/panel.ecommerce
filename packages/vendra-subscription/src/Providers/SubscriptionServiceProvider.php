<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Providers;

use Composer\InstalledVersions;

use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraSubscription\Console\Commands\ProvisionTenantCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class SubscriptionServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-subscription')
            ->hasCommands(
                ProvisionTenantCommand::class,
            )
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-subscription');
            });
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Subscription', fn() => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-subscription')]);
    }
}
