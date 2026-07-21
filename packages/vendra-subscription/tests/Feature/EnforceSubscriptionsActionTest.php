<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;
use Misaf\VendraSubscription\Actions\EnforceSubscriptionsAction;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraTenant\Models\Tenant;

/**
 * Create an account whose only subscription is active but past its end date.
 */
function lapsedAccount(int $graceDays, Carbon $endsAt): Account
{
    $account = Account::factory()->create();
    $plan = Plan::factory()->graceDays($graceDays)->create();

    Subscription::factory()->for($account)->for($plan)->create([
        'status'    => SubscriptionStatus::Active,
        'starts_at' => now()->subMonths(2),
        'ends_at'   => $endsAt,
    ]);

    return $account;
}

it('expires subscriptions whose period has lapsed', function (): void {
    $account = lapsedAccount(graceDays: 0, endsAt: now()->subDay());
    $subscription = $account->subscriptions()->sole();

    app(EnforceSubscriptionsAction::class)->execute();

    expect($subscription->refresh()->status)->toBe(SubscriptionStatus::Expired);
});

it('suspends websites once the grace period has passed', function (): void {
    $account = lapsedAccount(graceDays: 0, endsAt: now()->subDays(2));
    $website = Tenant::factory()->create(['account_id' => $account->getKey(), 'status' => true]);

    $result = app(EnforceSubscriptionsAction::class)->execute();

    expect($website->refresh()->status)->toBeFalse()
        ->and($result['suspended_websites'])->toBe(1)
        ->and($result['suspended_accounts'])->toBe(1);
});

it('keeps websites live while still within the grace period', function (): void {
    $account = lapsedAccount(graceDays: 10, endsAt: now()->subDay());
    $website = Tenant::factory()->create(['account_id' => $account->getKey(), 'status' => true]);

    app(EnforceSubscriptionsAction::class)->execute();

    expect($website->refresh()->status)->toBeTrue();
});

it('leaves websites of accounts with an active subscription untouched', function (): void {
    $account = Account::factory()->create();
    Subscription::factory()->for($account)->for(Plan::factory()->graceDays(0))->create();
    $website = Tenant::factory()->create(['account_id' => $account->getKey(), 'status' => true]);

    $result = app(EnforceSubscriptionsAction::class)->execute();

    expect($website->refresh()->status)->toBeTrue()
        ->and($result['suspended_websites'])->toBe(0);
});
