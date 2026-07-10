<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

it('registers multimedia api read routes', function (): void {
    expect(Route::has('vendra-multimedia.multimedia.index'))->toBeTrue()
        ->and(route('vendra-multimedia.multimedia.index', [], false))->toBe('/v1/multimedia');
});
