<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\Providers;

use Composer\InstalledVersions;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Misaf\VendraBlogApi\JsonApi\V1\Server as BlogServer;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class BlogApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-blog-api')
            ->hasRoute('api');
    }

    public function packageRegistered(): void
    {
        Config::set('jsonapi.servers.vendra-blog', Config::string('jsonapi.servers.vendra-blog', BlogServer::class));
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Blog API', fn(): array => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-blog-api')]);
    }
}
