<?php

declare(strict_types=1);

use Misaf\VendraGeo\Models\City;
use Misaf\VendraGeo\Models\Country;
use Misaf\VendraGeo\Models\State;
use Misaf\VendraTenant\Models\Tenant;

beforeEach(function (): void {
    Tenant::factory()->enabled()->create()->makeCurrent();
});

it('generates string slugs for geography models', function (): void {
    $country = Country::factory()->create([
        'name' => 'Test Country',
        'slug' => null,
    ]);
    $state = State::factory()->forCountry($country)->create([
        'name' => 'Test State',
        'slug' => null,
    ]);
    $city = City::factory()->forState($state)->create([
        'name' => 'Test City',
        'slug' => null,
    ]);

    expect($country->slug)->toBe('test-country')
        ->and($state->slug)->toBe('test-state')
        ->and($city->slug)->toBe('test-city');
});
