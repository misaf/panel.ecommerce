<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Providers;

use BezhanSalleh\LanguageSwitch\Enums\Placement;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraLanguage\Console\Commands\SeedCommand;
use Misaf\VendraLanguage\LanguagePlugin;
use Misaf\VendraLanguage\Models\Language;
use Misaf\VendraSupport\Filament\Concerns\ResolvesConfiguredPanels;
use Misaf\VendraSupport\Support\TenantSeeders;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class LanguageServiceProvider extends PackageServiceProvider
{
    use ResolvesConfiguredPanels;

    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-language')
            ->hasTranslations()
            ->hasMigrations([
                'create_languages_table',
                'add_tenant_id_column_to_language_lines_table',
            ])
            ->hasCommands(SeedCommand::class)
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-language');
            });
    }

    public function packageRegistered(): void
    {
        Panel::configureUsing(function (Panel $panel): void {
            if ( ! $this->shouldRegisterOnPanel($panel->getId(), 'vendra-language')) {
                return;
            }

            $panel->plugin(LanguagePlugin::make());
        });
    }

    public function packageBooted(): void
    {
        $this->app->make(TenantSeeders::class)->register('vendra-language:seed', priority: 80);

        $this->configureLanguageSwitch();

        AboutCommand::add('Vendra Language', fn() => ['Version' => 'dev-master']);
    }

    private function configureLanguageSwitch(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            return $switch
                ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER)
                ->locales($this->availableLocales())
                ->visible(outsidePanels: true)
                ->outsidePanelPlacement(Placement::TopCenter);
        });
    }

    /**
     * @return string[]
     */
    private function availableLocales(): array
    {
        return Language::where('status', true)
            ->pluck('iso_code')
            ->flatMap(fn(mixed $locale): array => is_string($locale) ? [$locale] : [])
            ->values()
            ->all();
    }
}
