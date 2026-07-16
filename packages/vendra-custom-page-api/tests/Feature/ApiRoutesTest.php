<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Misaf\VendraCustomPageApi\JsonApi\V1\Server;

it('registers custom page api read routes', function (): void {
    expect(Route::has('vendra-custom-page.custom-pages.index'))->toBeTrue()
        ->and(Route::has('vendra-custom-page.custom-page-categories.index'))->toBeTrue()
        ->and(route('vendra-custom-page.custom-pages.index', [], false))->toBe('/v1/custom-pages')
        ->and(route('vendra-custom-page.custom-page-categories.index', [], false))
        ->toBe('/v1/custom-page-categories');
});

it('registers its own json api server', function (): void {
    expect(config('jsonapi.servers.vendra-custom-page'))->toBe(Server::class);
});
