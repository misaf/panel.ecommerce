<?php

declare(strict_types=1);

use Misaf\VendraSubscription\Actions\CreateAccountAction;
use Misaf\VendraSubscription\Enums\PeriodUnit;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Exceptions\SubscriptionLimitException;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraSubscription\Support\WebsiteQuota;
use Misaf\VendraTenant\Models\Tenant;

function accountWithPlan(int $maxWebsites, int $existingWebsites = 0): Account
{
    $account = Account::factory()->create();

    Subscription::factory()
        ->for($account)
        ->for(Plan::factory()->maxWebsites($maxWebsites))
        ->create();

    Tenant::factory()->count($existingWebsites)->create(['account_id' => $account->getKey()]);

    return $account;
}

it('allows creating a website below the plan limit', function (): void {
    $account = accountWithPlan(maxWebsites: 2, existingWebsites: 1);
    $quota = app(WebsiteQuota::class);

    expect($quota->canCreateWebsite($account))->toBeTrue()
        ->and($quota->remainingWebsites($account))->toBe(1);

    $quota->assertCanCreateWebsite($account);
});

it('blocks creating a website at the plan limit', function (): void {
    $account = accountWithPlan(maxWebsites: 1, existingWebsites: 1);
    $quota = app(WebsiteQuota::class);

    expect($quota->canCreateWebsite($account))->toBeFalse()
        ->and($quota->remainingWebsites($account))->toBe(0);

    $quota->assertCanCreateWebsite($account);
})->throws(SubscriptionLimitException::class);

it('blocks website creation when no subscription is active', function (): void {
    $account = Account::factory()->create();
    Subscription::factory()->expired()->for($account)->for(Plan::factory()->maxWebsites(5))->create();

    $quota = app(WebsiteQuota::class);

    expect($quota->canCreateWebsite($account))->toBeFalse()
        ->and($quota->remainingWebsites($account))->toBe(0);

    $quota->assertCanCreateWebsite($account);
})->throws(SubscriptionLimitException::class);

it('creates an account subscribed to a plan for its period', function (): void {
    $plan = Plan::factory()->period(PeriodUnit::Month, 1)->create();

    $result = app(CreateAccountAction::class)->execute('Acme', $plan);

    expect($result['account'])->toBeInstanceOf(Account::class)
        ->and($result['account']->exists)->toBeTrue()
        ->and($result['subscription']->status)->toBe(SubscriptionStatus::Active)
        ->and($result['subscription']->plan_id)->toBe($plan->getKey())
        ->and($result['subscription']->ends_at->toDateString())
        ->toBe($result['subscription']->starts_at->copy()->addMonth()->toDateString())
        ->and($result['account']->activeSubscription()?->getKey())->toBe($result['subscription']->getKey());
});
