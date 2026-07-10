<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Providers;

use Filament\Panel;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Misaf\VendraAttribute\AttributePlugin;
use Misaf\VendraAttribute\Console\Commands\SeedCommand;
use Misaf\VendraAttribute\Models\Attribute;
use Misaf\VendraAttribute\Models\AttributeValue;
use Misaf\VendraSupport\Contracts\AttributeResolver;
use Misaf\VendraSupport\Support\EloquentAttributeResolver;
use Misaf\VendraSupport\Support\TenantSeeders;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class AttributeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-attribute')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigration('create_attributes_table')
            ->hasCommand(SeedCommand::class)
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-attribute');
            });
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(
            AttributeResolver::class,
            fn(): EloquentAttributeResolver => new EloquentAttributeResolver(Attribute::class, AttributeValue::class),
        );

        Panel::configureUsing(function (Panel $panel): void {
            if ( ! in_array($panel->getId(), $this->configuredPanelIds(), true)) {
                return;
            }

            $panel->plugin(AttributePlugin::make());
        });
    }

    public function packageBooted(): void
    {
        $this->app->make(TenantSeeders::class)->register('vendra-attribute:seed', priority: 35);

        AboutCommand::add('Vendra Attribute', fn() => ['Version' => 'dev-master']);
    }

    /** @return list<string> */
    private function configuredPanelIds(): array
    {
        $panels = Config::get('vendra-attribute.panels', ['admin']);

        if (is_string($panels)) {
            return [$panels];
        }

        return array_values(array_filter(Config::array('vendra-attribute.panels'), is_string(...)));
    }
}
