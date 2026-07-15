<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Misaf\VendraCurrency\Database\Factories\CurrencyFactory;
use Misaf\VendraCurrency\Enums\CurrencyCategoryPolicyEnum;
use Misaf\VendraCurrency\Enums\CurrencyPolicyEnum;
use Misaf\VendraCurrency\Models\Currency;
use Misaf\VendraCurrency\Models\CurrencyCategory;
use Misaf\VendraCurrency\Observers\CurrencyCategoryObserver;

it('defines the expected currency models', function (): void {
    expect((new CurrencyCategory())->getFillable())->toContain('name', 'description', 'slug', 'position', 'status')
        ->and((new Currency())->getFillable())->toContain('currency_category_id', 'name', 'description', 'slug', 'iso_code', 'conversion_rate', 'decimal_place', 'buy_price', 'sell_price', 'is_default', 'position', 'status')
        ->and((new CurrencyCategory())->getHidden())->toContain('tenant_id')
        ->and((new Currency())->getHidden())->toContain('tenant_id');
});

it('normalizes and generates three-letter currency codes', function (): void {
    $currency = new Currency();
    $currency->iso_code = 'usd';

    $factoryCode = CurrencyFactory::new()->definition()['iso_code'];

    expect($currency->iso_code)->toBe('USD')
        ->and($factoryCode)->toBeString()->toMatch('/^[A-Z]{3}$/');
});

it('defines the expected currency relationships', function (): void {
    expect((new ReflectionMethod(CurrencyCategory::class, 'currencies'))->getReturnType()?->getName())->toBe(HasMany::class)
        ->and((new ReflectionMethod(CurrencyCategory::class, 'multimedia'))->getReturnType()?->getName())->toBe(MorphMany::class)
        ->and((new ReflectionMethod(Currency::class, 'currencyCategory'))->getReturnType()?->getName())->toBe(BelongsTo::class)
        ->and((new ReflectionMethod(Currency::class, 'multimedia'))->getReturnType()?->getName())->toBe(MorphMany::class);
});

it('registers the cascade observer on currency categories', function (): void {
    $observerAttributes = (new ReflectionClass(CurrencyCategory::class))->getAttributes(ObservedBy::class);

    expect($observerAttributes)->toHaveCount(1)
        ->and($observerAttributes[0]->getArguments()[0])->toBe([CurrencyCategoryObserver::class]);
});

it('defines policy permissions for all currency resources', function (): void {
    expect(array_column(CurrencyCategoryPolicyEnum::cases(), 'value'))->toBe([
        'create-currency-category',
        'delete-currency-category',
        'delete-any-currency-category',
        'force-delete-currency-category',
        'force-delete-any-currency-category',
        'reorder-currency-category',
        'replicate-currency-category',
        'restore-currency-category',
        'restore-any-currency-category',
        'update-currency-category',
        'view-currency-category',
        'view-any-currency-category',
    ])
        ->and(array_column(CurrencyPolicyEnum::cases(), 'value'))->toBe([
            'create-currency',
            'delete-currency',
            'delete-any-currency',
            'force-delete-currency',
            'force-delete-any-currency',
            'reorder-currency',
            'replicate-currency',
            'restore-currency',
            'restore-any-currency',
            'update-currency',
            'view-currency',
            'view-any-currency',
        ]);
});
