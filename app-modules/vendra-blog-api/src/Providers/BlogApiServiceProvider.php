<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\Providers;

use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraBlogApi\JsonApi\V1\Server as BlogServer;
use Misaf\VendraMultimediaApi\JsonApi\V1\Server as MultimediaServer;
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
        config()->set('jsonapi.servers.vendra-blog', config('jsonapi.servers.vendra-blog', BlogServer::class));
        config()->set('jsonapi.servers.vendra-multimedia', config('jsonapi.servers.vendra-multimedia', MultimediaServer::class));
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Blog API', fn() => ['Version' => 'dev-master']);
    }
}
