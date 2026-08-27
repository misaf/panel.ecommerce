<?php

declare(strict_types=1);

use Misaf\VendraDelivery\Support\GeoDistance;

it('measures no distance between a point and itself', function (): void {
    expect(GeoDistance::kilometres(35.6892, 51.3890, 35.6892, 51.3890))->toBe(0.0);
});

it('measures a known distance across Tehran', function (): void {
    // Vali Asr Street to Tehran's western edge, roughly 12 km apart.
    $distance = GeoDistance::kilometres(35.6892, 51.3890, 35.7219, 51.2334);

    expect($distance)->toBeGreaterThan(13.0)->toBeLessThan(15.0);
});

it('is symmetric', function (): void {
    $there = GeoDistance::kilometres(35.6892, 51.3890, 35.7219, 51.3347);
    $back = GeoDistance::kilometres(35.7219, 51.3347, 35.6892, 51.3890);

    expect(round($there, 6))->toBe(round($back, 6));
});
