<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\Providers;

use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraAttributeApi\JsonApi\V1\Server as AttributeServer;
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
        config()->set('jsonapi.servers.vendra-attribute', config('jsonapi.servers.vendra-attribute', AttributeServer::class));
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Attribute API', fn() => ['Version' => 'dev-master']);
    }
}
