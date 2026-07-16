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
use Spatie\Sluggable\HasSlug;

it('defines the expected string-based geography models', function (): void {
    expect(class_uses_recursive(Country::class))->toContain(HasSlug::class)
        ->and(class_uses_recursive(State::class))->toContain(HasSlug::class)
        ->and(class_uses_recursive(City::class))->toContain(HasSlug::class)
        ->and((new Country())->getCasts())->toMatchArray(['name' => 'string', 'slug' => 'string'])
        ->and((new State())->getCasts())->toMatchArray(['name' => 'string', 'slug' => 'string'])
        ->and((new City())->getCasts())->toMatchArray(['name' => 'string', 'slug' => 'string'])
        ->and((new Country())->getFillable())->toContain('iso2', 'currency_code', 'status')
        ->and((new State())->getFillable())->toContain('country_id', 'type', 'status')
        ->and((new City())->getFillable())->toContain('country_id', 'state_id', 'status')
        ->and((new Country())->getHidden())->toContain('tenant_id')
        ->and((new State())->getHidden())->toContain('tenant_id')
        ->and((new City())->getHidden())->toContain('tenant_id');
});

it('stores geography names and slugs as strings', function (): void {
    $migration = file_get_contents(__DIR__ . '/../../database/migrations/create_geo_tables.php.stub');

    expect($migration)->not->toBeFalse()
        ->and(mb_substr_count($migration, "\$table->string('name');"))->toBe(3)
        ->and(mb_substr_count($migration, "\$table->string('slug');"))->toBe(3)
        ->and($migration)->not->toContain("\$table->json('name')")
        ->and($migration)->not->toContain("\$table->json('slug')");
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
