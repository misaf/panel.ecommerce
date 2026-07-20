<?php

declare(strict_types=1);

use Misaf\VendraCurrency\Actions\SetBuyPriceAction;
use Misaf\VendraCurrency\Actions\SetDefaultCurrencyAction;
use Misaf\VendraCurrency\Actions\SetSellPriceAction;
use Misaf\VendraCurrency\Models\Currency;
use Misaf\VendraCurrency\Models\CurrencyCategory;
use Misaf\VendraCurrency\Observers\CurrencyCategoryObserver;

beforeEach(function (): void {
    makeCurrentTestTenant();
});

it('promotes a currency to default and demotes the previous default', function (): void {
    $previous = Currency::factory()->create(['is_default' => true]);
    $next = Currency::factory()->create(['is_default' => false]);

    new SetDefaultCurrencyAction()->execute($next);

    expect($next->fresh()->is_default)->toBeTrue()
        ->and($previous->fresh()->is_default)->toBeFalse()
        ->and(Currency::where('is_default', true)->count())->toBe(1);
});

it('updates the buy price below the sell price', function (): void {
    $currency = Currency::factory()->create(['buy_price' => 100, 'sell_price' => 200]);

    expect(new SetBuyPriceAction()->execute($currency, 150))->toBeTrue()
        ->and($currency->fresh()->buy_price)->toBe(150);
});

it('rejects a buy price above the sell price', function (): void {
    $currency = Currency::factory()->create(['buy_price' => 100, 'sell_price' => 200]);

    expect(fn() => new SetBuyPriceAction()->execute($currency, 201))
        ->toThrow(Exception::class, 'Buy price cannot be greater than sell price.')
        ->and($currency->fresh()->buy_price)->toBe(100);
});

it('updates the sell price above the buy price', function (): void {
    $currency = Currency::factory()->create(['buy_price' => 100, 'sell_price' => 200]);

    expect(new SetSellPriceAction()->execute($currency, 120))->toBeTrue()
        ->and($currency->fresh()->sell_price)->toBe(120);
});

it('rejects a sell price below the buy price', function (): void {
    $currency = Currency::factory()->create(['buy_price' => 100, 'sell_price' => 200]);

    expect(fn() => new SetSellPriceAction()->execute($currency, 99))
        ->toThrow(Exception::class, 'Sell price must be greater than buy price.')
        ->and($currency->fresh()->sell_price)->toBe(200);
});

it('reports an unchanged price without touching the record', function (): void {
    $currency = Currency::factory()->create(['buy_price' => 100, 'sell_price' => 200]);

    expect(new SetBuyPriceAction()->execute($currency, 100))->toBeFalse()
        ->and(new SetSellPriceAction()->execute($currency, 200))->toBeFalse();
});

it('deletes the currencies of a deleted currency category', function (): void {
    $category = CurrencyCategory::factory()->create();
    $currency = Currency::factory()->create(['currency_category_id' => $category->id]);

    new CurrencyCategoryObserver()->deleted($category);

    expect($currency->fresh()->trashed())->toBeTrue();
});
