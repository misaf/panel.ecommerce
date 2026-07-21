<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\Providers;

use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Config;
use Misaf\VendraAttributeApi\JsonApi\V1\Server as AttributeServer;
use Misaf\VendraAttributeApi\Support\AttributeApiServiceResolver;
use Misaf\VendraSupport\Contracts\AttributeApiResolver;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class AttributeApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-attribute-api')
            ->hasRoute('api');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(AttributeApiResolver::class, AttributeApiServiceResolver::class);

        Config::set('jsonapi.servers.vendra-attribute', Config::string('jsonapi.servers.vendra-attribute', AttributeServer::class));
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Attribute API', fn(): array => ['Version' => InstalledVersions::getPrettyVersion('misaf/vendra-attribute-api')]);
    }
}
