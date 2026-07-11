<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Misaf\VendraMultimediaApi\JsonApi\V1\Server;

it('registers multimedia api read routes', function (): void {
    expect(Route::has('vendra-multimedia.multimedia.index'))->toBeTrue()
        ->and(route('vendra-multimedia.multimedia.index', [], false))->toBe('/v1/multimedia');
});

it('registers its own json:api server', function (): void {
    expect(config('jsonapi.servers.vendra-multimedia'))->toBe(Server::class);
});

it('serves the multimedia index through the self-registered server', function (): void {
    $this->getJson('/v1/multimedia', ['Accept' => 'application/vnd.api+json'])
        ->assertOk();
});
