<?php

declare(strict_types=1);

use Misaf\VendraSubscription\Actions\SubscribeAccountAction;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraTenant\Models\Tenant;

it('cancels the previous active subscription when changing plans', function (): void {
    $account = Account::factory()->create();
    $old = Subscription::factory()->for($account)->for(Plan::factory()->maxWebsites(1))->create();
    $newPlan = Plan::factory()->maxWebsites(5)->create();

    $new = app(SubscribeAccountAction::class)->execute($account, $newPlan);

    expect($old->refresh()->status)->toBe(SubscriptionStatus::Cancelled)
        ->and($new->status)->toBe(SubscriptionStatus::Active)
        ->and($new->plan_id)->toBe($newPlan->getKey())
        ->and($account->activeSubscription()?->getKey())->toBe($new->getKey())
        ->and($account->subscriptions()->active()->count())->toBe(1);
});

it('renews by creating a fresh active subscription for the same plan', function (): void {
    $account = Account::factory()->create();
    $plan = Plan::factory()->maxWebsites(2)->create();
    Subscription::factory()->expired()->for($account)->for($plan)->create();

    $renewed = app(SubscribeAccountAction::class)->execute($account, $plan);

    expect($renewed->isActive())->toBeTrue()
        ->and($account->activeSubscription()?->getKey())->toBe($renewed->getKey());
});

it('reactivates suspended websites when the account resubscribes', function (): void {
    $account = Account::factory()->create();
    $website = Tenant::factory()->create(['account_id' => $account->getKey(), 'status' => false]);

    app(SubscribeAccountAction::class)->execute($account, Plan::factory()->create());

    expect($website->refresh()->status)->toBeTrue();
});
