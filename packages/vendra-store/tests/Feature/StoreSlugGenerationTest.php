<?php

declare(strict_types=1);

use Misaf\VendraStore\Models\Store;

it('generates a slug from the name when none is provided', function (): void {
    $tenant = Store::factory()->create([
        'name' => 'Hello World',
        'slug' => null,
    ]);

    expect($tenant->slug)->toBe('hello-world');
});

it('keeps a manually provided slug and never overwrites it', function (): void {
    $tenant = Store::factory()->create(['slug' => 'custom-slug']);

    $tenant->update(['name' => 'Changed Name']);

    expect($tenant->refresh()->slug)->toBe('custom-slug');
});
