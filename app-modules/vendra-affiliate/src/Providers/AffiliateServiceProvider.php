<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Providers;

use Filament\Panel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Event;
use Misaf\VendraAffiliate\AffiliatePlugin;
use Misaf\VendraAffiliate\Listeners\AffiliateSubscriber;
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraAffiliate\Services\AffiliateService;
use Misaf\VendraUser\Models\User;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class AffiliateServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-affiliate')
            ->hasTranslations()
            ->hasMigrations([
                'create_affiliates_table'
            ])
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-affiliate');
            });
    }

    public function packageRegistered(): void
    {
        $this->app->bind('affiliate-service', fn(Application $app) => new AffiliateService());

        Panel::configureUsing(function (Panel $panel): void {
            if ('admin' !== $panel->getId()) {
                return;
            }

            $panel->plugin(AffiliatePlugin::make());
        });
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Affiliate', fn() => ['Version' => 'dev-master']);

        User::resolveRelationUsing('affiliates', fn(User $user): HasMany => $user->hasMany(Affiliate::class));

        Event::subscribe(AffiliateSubscriber::class);
    }

    /**
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            'affiliate-service',
        ];
    }
}
