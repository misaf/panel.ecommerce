<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Misaf\VendraGeo\Enums\CityPolicyEnum;
use Misaf\VendraGeo\Enums\CountryPolicyEnum;
use Misaf\VendraGeo\Enums\StatePolicyEnum;
use Misaf\VendraGeo\Models\City;
use Misaf\VendraGeo\Models\Country;
use Misaf\VendraGeo\Models\State;
use Misaf\VendraGeo\Observers\CountryObserver;
use Misaf\VendraGeo\Observers\StateObserver;

it('defines the expected translatable geography models', function (): void {
    expect((new Country())->translatable)->toBe(['name', 'slug'])
        ->and((new State())->translatable)->toBe(['name', 'slug'])
        ->and((new City())->translatable)->toBe(['name', 'slug'])
        ->and((new Country())->getFillable())->toContain('iso2', 'currency_code', 'status')
        ->and((new State())->getFillable())->toContain('country_id', 'type', 'status')
        ->and((new City())->getFillable())->toContain('country_id', 'state_id', 'status')
        ->and((new Country())->getHidden())->toContain('tenant_id')
        ->and((new State())->getHidden())->toContain('tenant_id')
        ->and((new City())->getHidden())->toContain('tenant_id');
});

it('registers cascade observers on parent geography models', function (): void {
    $countryObserverAttributes = (new ReflectionClass(Country::class))->getAttributes(ObservedBy::class);
    $stateObserverAttributes = (new ReflectionClass(State::class))->getAttributes(ObservedBy::class);

    expect($countryObserverAttributes)->toHaveCount(1)
        ->and($countryObserverAttributes[0]->getArguments()[0])->toBe([CountryObserver::class])
        ->and($stateObserverAttributes)->toHaveCount(1)
        ->and($stateObserverAttributes[0]->getArguments()[0])->toBe([StateObserver::class]);
});

it('defines policy permissions for all geo resources', function (): void {
    expect(array_column(CountryPolicyEnum::cases(), 'value'))->toBe([
        'create-country',
        'delete-country',
        'delete-any-country',
        'force-delete-country',
        'force-delete-any-country',
        'reorder-country',
        'replicate-country',
        'restore-country',
        'restore-any-country',
        'update-country',
        'view-country',
        'view-any-country',
    ])
        ->and(array_column(StatePolicyEnum::cases(), 'value'))->toContain('create-state', 'delete-any-state', 'view-any-state')
        ->and(array_column(CityPolicyEnum::cases(), 'value'))->toContain('create-city', 'delete-any-city', 'view-any-city');
});
