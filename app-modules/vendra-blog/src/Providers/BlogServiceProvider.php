<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Providers;

use Filament\Panel;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Misaf\VendraBlog\BlogPlugin;
use Misaf\VendraBlog\Console\Commands\SeedCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class BlogServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-blog')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigrations([
                'create_blogs_table',
            ])
            ->hasCommands(SeedCommand::class)
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-blog');
            });
    }

    public function packageRegistered(): void
    {
        Panel::configureUsing(function (Panel $panel): void {
            if ( ! in_array($panel->getId(), $this->configuredPanelIds(), true)) {
                return;
            }

            $panel->plugin(BlogPlugin::make());
        });
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Blog', fn() => ['Version' => 'dev-master']);
    }

    /**
     * @return array<int, string>
     */
    private function configuredPanelIds(): array
    {
        $panels = Config::get('vendra-blog.panels');

        if (is_string($panels)) {
            return [Config::string('vendra-blog.panels')];
        }

        if (is_array($panels)) {
            return $this->filterPanelIds(Config::array('vendra-blog.panels'));
        }

        $legacyPanel = Config::get('vendra-blog.panel');

        if (is_string($legacyPanel)) {
            return [Config::string('vendra-blog.panel')];
        }

        if (is_array($legacyPanel)) {
            return $this->filterPanelIds(Config::array('vendra-blog.panel'));
        }

        return ['admin'];
    }

    /**
     * @param  array<mixed>  $panels
     * @return array<int, string>
     */
    private function filterPanelIds(array $panels): array
    {
        return array_values(array_filter($panels, is_string(...)));
    }
}
