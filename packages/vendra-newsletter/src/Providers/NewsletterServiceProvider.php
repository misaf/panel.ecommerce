<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Providers;

use Composer\InstalledVersions;

use Filament\Panel;
use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraNewsletter\Console\Commands\Newsletter\SendCommand as NewsletterSendCommand;
use Misaf\VendraNewsletter\Console\Commands\NewsletterPost\SendCommand as NewsletterPostSendCommand;
use Misaf\VendraNewsletter\Console\Commands\SendScheduledNewslettersCommand;
use Misaf\VendraNewsletter\Console\Commands\SyncSubscribersWithUsersCommand;
use Misaf\VendraNewsletter\NewsletterPlugin;
use Misaf\VendraSupport\Filament\Concerns\ResolvesConfiguredPanels;
use Misaf\VendraSupport\Support\TenantSeeders;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class NewsletterServiceProvider extends PackageServiceProvider
{
    use ResolvesConfiguredPanels;

    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-newsletter')
            ->hasTranslations()
            ->hasConfigFile()
            ->hasMigrations([
                'create_newsletters_table',
            ])
            ->hasRoute('web')
            ->hasViews()
            ->hasCommands(
                SendScheduledNewslettersCommand::class,
                SyncSubscribersWithUsersCommand::class,
                NewsletterSendCommand::class,
                NewsletterPostSendCommand::class,
            )
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-newsletter');
            });
    }

    public function packageRegistered(): void
    {
        Panel::configureUsing(function (Panel $panel): void {
            if ( ! $this->shouldRegisterOnPanel($panel->getId(), 'vendra-newsletter')) {
                return;
            }

            $panel->plugin(NewsletterPlugin::make());
        });
    }

    public function packageBooted(): void
    {
        $this->app->make(TenantSeeders::class)->register('vendra-newsletter:seed', priority: 65);

        AboutCommand::add('Vendra Newsletter', fn() => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-newsletter')]);
    }
}
