<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;
use Misaf\VendraSubscription\Enums\PeriodUnit;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraTenant\Models\Tenant;

it('relates an account to its websites and subscriptions', function (): void {
    $account = Account::factory()
        ->has(Tenant::factory()->count(2))
        ->has(Subscription::factory())
        ->create();

    expect($account->tenants)->toHaveCount(2)
        ->and($account->tenants->first())->toBeInstanceOf(Tenant::class)
        ->and($account->subscriptions)->toHaveCount(1);
});

it('returns the active subscription and ignores expired or cancelled ones', function (): void {
    $account = Account::factory()->create();

    Subscription::factory()->expired()->for($account)->create();
    Subscription::factory()->cancelled()->for($account)->create();
    $active = Subscription::factory()->for($account)->create();

    expect($account->activeSubscription()?->getKey())->toBe($active->getKey());
});

it('reports no active subscription when none are active', function (): void {
    $account = Account::factory()->create();

    Subscription::factory()->expired()->for($account)->create();

    expect($account->activeSubscription())->toBeNull();
});

it('treats a subscription without an end date as active', function (): void {
    $subscription = Subscription::factory()->neverExpires()->create();

    expect($subscription->isActive())->toBeTrue();
});

it('treats an expired subscription as inactive', function (): void {
    $subscription = Subscription::factory()->expired()->create();

    expect($subscription->isActive())->toBeFalse();
});

it('resolves the plan end date from its period', function (): void {
    $plan = Plan::factory()->period(PeriodUnit::Month, 3)->create();

    $start = Carbon::parse('2026-01-01 00:00:00');

    expect($plan->resolveEndDate($start)->toDateString())->toBe('2026-04-01');
});
