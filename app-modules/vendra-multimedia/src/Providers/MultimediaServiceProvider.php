<?php

declare(strict_types=1);

namespace Misaf\VendraMultimedia\Providers;

use Filament\Panel;
use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraMultimedia\Models\Multimedia;
use Misaf\VendraMultimedia\MultimediaPlugin;
use Misaf\VendraMultimedia\Support\DefaultPathGenerator;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator as SpatieDefaultPathGenerator;

final class MultimediaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-multimedia')
            ->hasTranslations()
            ->hasMigrations([
                'add_tenant_id_column_to_media_table',
            ])
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-multimedia');
            });
    }

    public function packageRegistered(): void
    {
        Panel::configureUsing(function (Panel $panel): void {
            if ('admin' !== $panel->getId()) {
                return;
            }

            $panel->plugin(MultimediaPlugin::make());
        });

        $this->registerMediaLibraryDefaults();
    }

    /**
     * Make Spatie Media Library work without a published config/media-library.php:
     * point it at the Vendra multimedia classes and apply the behavioural defaults
     * this package standardises on.
     *
     * Runs in register() (not boot()) on purpose: Spatie's packageBooted() captures
     * media_model to attach the MediaObserver, so the value must be set before any
     * provider boots. The class settings keep an explicit host override untouched;
     * the boolean settings are forced because a boolean value cannot be told apart
     * from an app that deliberately chose the same value.
     */
    private function registerMediaLibraryDefaults(): void
    {
        $mediaModel = config('media-library.media_model');

        if (null === $mediaModel || SpatieMedia::class === $mediaModel) {
            config(['media-library.media_model' => Multimedia::class]);
        }

        $pathGenerator = config('media-library.path_generator');

        if (null === $pathGenerator || SpatieDefaultPathGenerator::class === $pathGenerator) {
            config(['media-library.path_generator' => DefaultPathGenerator::class]);
        }

        // Literals, not env(): env() returns its fallback once config is cached.
        config([
            'media-library.queue_conversions_by_default' => false,
            'media-library.moves_media_on_update'        => true,
        ]);
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Multimedia', fn() => ['Version' => 'dev-master']);
    }
}
