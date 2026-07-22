<?php

declare(strict_types=1);

use App\Actions\SubscribeResellerAction;
use App\Models\Reseller;
use Misaf\VendraSubscription\Models\Plan;

it('stores a plan price and currency', function (): void {
    $plan = Plan::factory()->priced(1500, 'USD')->create();

    expect($plan->price)->toBe(1500)
        ->and($plan->currency_code)->toBe('USD')
        ->and($plan->isFree())->toBeFalse();
});

it('treats a zero-price plan as free', function (): void {
    $plan = Plan::factory()->create();

    expect($plan->isFree())->toBeTrue();
});

it('snapshots the plan price onto the subscription when subscribing', function (): void {
    $reseller = Reseller::factory()->create();
    $plan = Plan::factory()->priced(2999, 'EUR')->create();

    $subscription = app(SubscribeResellerAction::class)->execute($reseller, $plan);

    expect($subscription->price)->toBe(2999)
        ->and($subscription->currency_code)->toBe('EUR');
});
