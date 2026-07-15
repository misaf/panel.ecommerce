<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Providers;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Composer\InstalledVersions;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraLanguage\Console\Commands\SeedCommand;
use Misaf\VendraLanguage\LanguagePlugin;
use Misaf\VendraLanguage\Localization\LanguageSwitchLocaleResolver;
use Misaf\VendraLanguage\Localization\NamespacedTranslationLoaderManager;
use Misaf\VendraLanguage\Localization\TenantLocaleResolver;
use Misaf\VendraLanguage\Models\Language;
use Misaf\VendraLanguage\Models\LanguageLine;
use Misaf\VendraLanguage\Support\Locales;
use Misaf\VendraLocalization\Contracts\LocaleResolver;
use Misaf\VendraLocalization\Resolvers\QueryLocaleResolver;
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
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigrations([
                'create_languages_table',
                'add_tenant_id_column_to_language_lines_table',
                'enforce_language_invariants',
                'add_namespace_to_language_lines_table',
            ])
            ->hasCommands(SeedCommand::class)
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-language');
            });
    }

    public function packageRegistered(): void
    {
        config([
            'translation-loader.model'               => LanguageLine::class,
            'translation-loader.translation_manager' => NamespacedTranslationLoaderManager::class,
        ]);

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

        $this->configureLocalization();

        AboutCommand::add('Vendra Language', fn() => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-language')]);
    }

    /**
     * Bridge the language catalog into the localization module: the platform
     * locales become the supported set, and the tenant's default language is
     * appended to the resolver chain as the lowest-priority baseline.
     */
    private function configureLocalization(): void
    {
        if ( ! interface_exists(LocaleResolver::class) || ! config()->has('vendra-localization.resolvers')) {
            return;
        }

        config(['vendra-localization.supported_locales' => Locales::configured()]);

        $resolvers = config()->array('vendra-localization.resolvers');

        if ( ! in_array(LanguageSwitchLocaleResolver::class, $resolvers, true)) {
            $queryResolverIndex = array_search(QueryLocaleResolver::class, $resolvers, true);
            $offset = is_int($queryResolverIndex) ? $queryResolverIndex + 1 : 0;

            array_splice($resolvers, $offset, 0, [LanguageSwitchLocaleResolver::class]);
        }

        if ( ! in_array(TenantLocaleResolver::class, $resolvers, true)) {
            $resolvers[] = TenantLocaleResolver::class;
        }

        config(['vendra-localization.resolvers' => $resolvers]);
    }

    private function configureLanguageSwitch(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            return $switch
                ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER)
                ->locales(fn(): array => $this->availableLocales())
                ->visible();
        });
    }

    /**
     * The enabled locales for the current tenant, in display order. Falls back
     * to the application fallback locale when a tenant has none enabled yet.
     *
     * @return string[]
     */
    private function availableLocales(): array
    {
        $locales = Language::query()
            ->ordered()
            ->pluck('locale')
            ->flatMap(fn(mixed $locale): array => is_string($locale) ? [$locale] : [])
            ->values()
            ->all();

        return [] === $locales ? [config()->string('app.fallback_locale')] : $locales;
    }
}
