<?php

declare(strict_types=1);

use Misaf\VendraCurrency\Models\Currency;
use Misaf\VendraTenant\Models\Tenant;

beforeEach(function (): void {
    Tenant::factory()->enabled()->create()->makeCurrent();
});

it('generates a slug from the name and de-duplicates collisions', function (): void {
    $first = Currency::factory()->create(['name' => 'Gold Coin', 'slug' => null]);
    $second = Currency::factory()->create(['name' => 'Gold Coin', 'slug' => null]);

    expect($first->slug)->toBe('gold-coin')
        ->and($second->slug)->toBe('gold-coin-1');
});
