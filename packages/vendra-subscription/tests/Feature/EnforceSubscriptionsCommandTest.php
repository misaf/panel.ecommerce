<?php

declare(strict_types=1);

use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraTenant\Models\Tenant;

it('expires subscriptions and suspends websites via the command', function (): void {
    $account = Account::factory()->create();
    Subscription::factory()->for($account)->for(Plan::factory()->graceDays(0))->create([
        'status'    => SubscriptionStatus::Active,
        'starts_at' => now()->subMonths(2),
        'ends_at'   => now()->subDays(2),
    ]);
    $website = Tenant::factory()->create(['account_id' => $account->getKey(), 'status' => true]);

    $this->artisan('vendra-subscription:enforce-subscriptions')->assertSuccessful();

    expect($website->refresh()->status)->toBeFalse()
        ->and($account->subscriptions()->sole()->status)->toBe(SubscriptionStatus::Expired);
});
