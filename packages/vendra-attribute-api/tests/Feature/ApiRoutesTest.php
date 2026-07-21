<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

it('registers attribute api read routes', function (): void {
    expect(Route::has('vendra-attribute.attributes.index'))->toBeTrue()
        ->and(route('vendra-attribute.attributes.index', [], false))->toBe('/v1/attributes')
        ->and(Route::has('vendra-attribute.attribute-values.index'))->toBeTrue()
        ->and(route('vendra-attribute.attribute-values.index', [], false))->toBe('/v1/attribute-values');
});
