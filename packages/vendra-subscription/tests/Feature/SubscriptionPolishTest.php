<?php

declare(strict_types=1);

use Misaf\VendraSubscription\Actions\SubscribeAccountAction;
use Misaf\VendraSubscription\Database\Seeders\PlanSeeder;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;

it('starts a trial on the account\'s first subscription only', function (): void {
    $account = Account::factory()->create();

    $first = app(SubscribeAccountAction::class)->execute($account, Plan::factory()->trialDays(14)->create());
    $second = app(SubscribeAccountAction::class)->execute($account, Plan::factory()->trialDays(14)->create());

    expect($first->trial_ends_at)->not->toBeNull()
        ->and($first->isOnTrial())->toBeTrue()
        ->and($second->trial_ends_at)->toBeNull();
});

it('resolves plan and account feature entitlements', function (): void {
    $account = Account::factory()->create();
    $plan = Plan::factory()->withFeatures(['custom_domain'])->create();
    Subscription::factory()->for($account)->for($plan)->create();

    expect($plan->allows('custom_domain'))->toBeTrue()
        ->and($plan->allows('priority_support'))->toBeFalse()
        ->and($account->allows('custom_domain'))->toBeTrue()
        ->and($account->allows('priority_support'))->toBeFalse();
});

it('seeds the default plans idempotently', function (): void {
    (new PlanSeeder())->run();
    (new PlanSeeder())->run();

    expect(Plan::query()->count())->toBe(3)
        ->and(Plan::query()->where('slug', 'pro')->sole()->allows('priority_support'))->toBeTrue();
});
