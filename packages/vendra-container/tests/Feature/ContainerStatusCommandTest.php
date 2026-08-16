<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

/**
 * @param array<string, string> $headers
 */
function fakeEngineForStatus(array $headers = [], bool $networkExists = true): void
{
    Http::fake([
        '*/_ping'      => Http::response('OK', headers: $headers),
        '*/networks/*' => Http::response(
            $networkExists ? ['Name' => 'traefik-public'] : ['message' => 'network not found'],
            $networkExists ? 200 : 404,
        ),
    ]);
}

beforeEach(function (): void {
    Config::set('vendra-container.endpoint', 'unix:///var/run/docker.sock');
    Config::set('vendra-container.runtime', 'docker');
});

it('reports the configured runtime alongside the engine that answered', function (): void {
    fakeEngineForStatus(['Server' => 'Docker/29.7.2 (linux)']);

    $this->artisan('container:status')
        ->expectsOutputToContain('Configured runtime')
        ->expectsOutputToContain('unix:///var/run/docker.sock')
        ->expectsOutputToContain('Docker/29.7.2 (linux)')
        ->assertSuccessful();
});

it('warns and fails when the endpoint is serving a different engine than configured', function (): void {
    fakeEngineForStatus(['Server' => 'Libpod/5.8.6 (linux)']);

    $this->artisan('container:status')
        ->expectsOutputToContain('This endpoint is serving podman, but CONTAINER_RUNTIME is set to docker')
        ->assertFailed();
});

it('stays quiet when the engine is the configured one', function (): void {
    fakeEngineForStatus(['Server' => 'Docker/29.7.2 (linux)']);

    $this->artisan('container:status')
        ->doesntExpectOutputToContain('CONTAINER_RUNTIME is set to')
        ->assertSuccessful();
});

it('reports a requested network against that daemon', function (): void {
    fakeEngineForStatus(['Server' => 'Docker/29.7.2 (linux)']);

    $this->artisan('container:status --network=traefik-public')
        ->expectsOutputToContain('The network [traefik-public] exists on this daemon.')
        ->assertSuccessful();
});

it('fails when a requested network is missing', function (): void {
    fakeEngineForStatus(['Server' => 'Docker/29.7.2 (linux)'], networkExists: false);

    $this->artisan('container:status --network=traefik-public')
        ->expectsOutputToContain('does not exist on this daemon')
        ->assertFailed();
});

it('fails without reaching the network when the daemon does not answer', function (): void {
    Http::fake(['*' => Http::response(['message' => 'no such file'], 500)]);

    $this->artisan('container:status --network=traefik-public')
        ->doesntExpectOutputToContain('traefik-public')
        ->assertFailed();
});

it('refuses to guess at an endpoint that was never configured', function (): void {
    Config::set('vendra-container.endpoint', '');

    $this->artisan('container:status')
        ->expectsOutputToContain('Configure CONTAINER_ENDPOINT first.')
        ->assertFailed();

    Http::assertNothingSent();
});
