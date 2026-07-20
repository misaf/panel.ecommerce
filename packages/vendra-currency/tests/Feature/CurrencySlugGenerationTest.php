<?php

declare(strict_types=1);

use Misaf\VendraCurrency\Models\Currency;

beforeEach(function (): void {
    makeCurrentTestTenant();
});

it('generates a slug from the name and de-duplicates collisions', function (): void {
    $first = Currency::factory()->create(['name' => 'Gold Coin', 'slug' => null]);
    $second = Currency::factory()->create(['name' => 'Gold Coin', 'slug' => null]);

    expect($first->slug)->toBe('gold-coin')
        ->and($second->slug)->toBe('gold-coin-1');
});
