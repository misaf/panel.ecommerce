<?php

declare(strict_types=1);

use Misaf\VendraSubscription\Actions\SubscribeAccountAction;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Exceptions\SubscriptionLimitException;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraTenant\Models\TenantDomain;

it('blocks changing to a plan that cannot hold the current websites', function (): void {
    $account = Account::factory()->create();
    Tenant::factory()->count(2)->create(['account_id' => $account->getKey()]);

    app(SubscribeAccountAction::class)->execute($account, Plan::factory()->maxWebsites(1)->create());
})->throws(SubscriptionLimitException::class);

it('allows renewing the same plan while at capacity', function (): void {
    $account = Account::factory()->create();
    Tenant::factory()->count(2)->create(['account_id' => $account->getKey()]);

    $subscription = app(SubscribeAccountAction::class)->execute($account, Plan::factory()->maxWebsites(2)->create());

    expect($subscription->isActive())->toBeTrue();
});

it('soft-deletes websites and cancels the subscription when an account is deleted', function (): void {
    $account = Account::factory()->create();
    $subscription = Subscription::factory()->for($account)->for(Plan::factory())->create();
    $website = Tenant::factory()->create(['account_id' => $account->getKey()]);
    $domain = TenantDomain::factory()->for($website)->create();

    $account->delete();

    expect(Tenant::query()->whereKey($website->getKey())->exists())->toBeFalse()
        ->and(TenantDomain::query()->whereKey($domain->getKey())->exists())->toBeFalse()
        ->and($subscription->refresh()->status)->toBe(SubscriptionStatus::Cancelled)
        ->and($account->fresh()?->trashed())->toBeTrue();
});
