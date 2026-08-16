<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Providers;

use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use Misaf\VendraContainer\Console\Commands\ContainerStatusCommand;
use Misaf\VendraContainer\Contracts\ContainerRuntime;
use Misaf\VendraContainer\Http\EngineApiClient;
use Misaf\VendraContainer\Runtimes\DockerRuntime;
use Misaf\VendraContainer\Runtimes\PodmanRuntime;
use Misaf\VendraContainer\Support\ContainerRuntimeConfiguration;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Binds the one runtime this installation uses.
 *
 * Selection happens here and only here: callers type-hint
 * {@see ContainerRuntime} and never learn which implementation answered. That is
 * what keeps `if ($runtime === 'docker')` out of every layer above.
 */
final class ContainerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-container')
            ->hasConfigFile()
            ->hasCommand(ContainerStatusCommand::class)
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->publishConfigFile();
            });
    }

    public function packageRegistered(): void
    {
        /*
         | Bound rather than shared: a configuration change — a test pointing at
         | a different endpoint, an operator reloading config — is picked up on
         | the next resolve instead of frozen at first use.
         */
        $this->app->bind(ContainerRuntimeConfiguration::class, static fn(): ContainerRuntimeConfiguration => ContainerRuntimeConfiguration::fromConfig());

        $this->app->bind(EngineApiClient::class, static function ($app): EngineApiClient {
            $configuration = $app->make(ContainerRuntimeConfiguration::class);

            return new EngineApiClient(
                endpoint: $configuration->endpoint,
                apiVersion: $configuration->apiVersion,
                timeout: $configuration->timeout,
            );
        });

        $this->app->bind(ContainerRuntime::class, static function ($app): ContainerRuntime {
            $configuration = $app->make(ContainerRuntimeConfiguration::class);
            $engine = $app->make(EngineApiClient::class);

            return $configuration->usesPodman()
                ? new PodmanRuntime($engine, $configuration->pullTimeout)
                : new DockerRuntime($engine, $configuration->pullTimeout);
        });
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Container', fn(): array => [
            'Version' => InstalledVersions::getPrettyVersion('misaf/vendra-container'),
            'Runtime' => fn(): string => ContainerRuntimeConfiguration::fromConfig()->runtime,
        ]);
    }
}
