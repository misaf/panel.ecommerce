<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Misaf\VendraContainer\Contracts\ContainerRuntime;
use Misaf\VendraContainer\Http\EngineApiClient;
use Misaf\VendraContainer\Runtimes\DockerRuntime;
use Misaf\VendraContainer\Runtimes\PodmanRuntime;
use Misaf\VendraContainer\Support\ContainerRuntimeConfiguration;

it('resolves the docker runtime by default', function (): void {
    Config::set('vendra-container.runtime', 'docker');

    expect(app(ContainerRuntime::class))->toBeInstanceOf(DockerRuntime::class);
});

it('resolves the podman runtime from configuration alone', function (): void {
    Config::set('vendra-container.runtime', 'podman');

    expect(app(ContainerRuntime::class))->toBeInstanceOf(PodmanRuntime::class);
});

it('falls back to the docker adapter for an unknown runtime name', function (): void {
    Config::set('vendra-container.runtime', 'contianer-d-typo');

    expect(app(ContainerRuntime::class))->toBeInstanceOf(DockerRuntime::class)
        ->and(app(ContainerRuntimeConfiguration::class)->runtime)->toBe('docker');
});

it('defaults each runtime to the API version its socket serves', function (): void {
    Config::set('vendra-container.api_version', '');
    Config::set('vendra-container.runtime', 'docker');

    expect(app(ContainerRuntimeConfiguration::class)->apiVersion)->toBe(DockerRuntime::DEFAULT_API_VERSION);

    Config::set('vendra-container.runtime', 'podman');

    expect(app(ContainerRuntimeConfiguration::class)->apiVersion)->toBe(PodmanRuntime::DEFAULT_API_VERSION);
});

it('lets configuration override the negotiated API version', function (): void {
    Config::set('vendra-container.runtime', 'podman');
    Config::set('vendra-container.api_version', 'v1.40');

    expect(app(EngineApiClient::class)->apiVersion())->toBe('v1.40');
});

it('picks up a configuration change on the next resolve', function (): void {
    Config::set('vendra-container.endpoint', 'unix:///first.sock');

    expect(app(ContainerRuntimeConfiguration::class)->endpoint)->toBe('unix:///first.sock');

    Config::set('vendra-container.endpoint', 'unix:///second.sock');

    expect(app(ContainerRuntimeConfiguration::class)->endpoint)->toBe('unix:///second.sock');
});

it('reports whether a runtime is configured at all without touching the network', function (): void {
    Config::set('vendra-container.endpoint', '');

    $configuration = app(ContainerRuntimeConfiguration::class);

    expect($configuration->isConfigured())->toBeFalse()
        ->and($configuration->misconfigurationMessage())->toContain('CONTAINER_ENDPOINT');

    Config::set('vendra-container.endpoint', 'unix:///var/run/docker.sock');

    expect(app(ContainerRuntimeConfiguration::class)->isConfigured())->toBeTrue();
});
