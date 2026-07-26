<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

it('round-trips the classes Pulse dashboard cards cache', function (): void {
    Cache::store()->put('pulse-probe', collect([(object) ['hits' => 1]]), 30);

    $value = Cache::store()->get('pulse-probe');

    expect($value)->toBeInstanceOf(Collection::class)
        ->and($value->first())->toBeInstanceOf(stdClass::class)
        ->and($value->first()->hits)->toBe(1);
});
