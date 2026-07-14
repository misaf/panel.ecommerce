<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

it('registers affiliate api read routes', function (): void {
    expect(Route::has('vendra-affiliate.affiliates.index'))->toBeTrue()
        ->and(Route::has('vendra-affiliate.affiliates.show'))->toBeTrue()
        ->and(route('vendra-affiliate.affiliates.index', [], false))->toBe('/v1/affiliates');
});

it('does not register write routes', function (): void {
    expect(Route::has('vendra-affiliate.affiliates.store'))->toBeFalse()
        ->and(Route::has('vendra-affiliate.affiliates.update'))->toBeFalse()
        ->and(Route::has('vendra-affiliate.affiliates.destroy'))->toBeFalse();
});
