<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\Providers;

use ApiPlatform\State\ProviderInterface;
use Composer\InstalledVersions;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Misaf\VendraBlogApi\State\BlogPostCategoryResourceProvider;
use Misaf\VendraBlogApi\State\BlogPostResourceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class BlogApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-blog-api');
    }

    public function packageRegistered(): void
    {
        Config::set('api-platform.resources', [
            ...Config::array('api-platform.resources', []),
            dirname(__DIR__) . '/ApiResource',
        ]);

        $this->app->tag([
            BlogPostResourceProvider::class,
            BlogPostCategoryResourceProvider::class,
        ], ProviderInterface::class);
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Blog API', fn(): array => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-blog-api')]);
    }
}
