<?php

declare(strict_types=1);

use App\Actions\SubscribeResellerAction;
use App\Models\Reseller;
use Misaf\VendraSubscription\Database\Seeders\PlanSeeder;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;

it('starts a trial on the reseller\'s first subscription only', function (): void {
    $reseller = Reseller::factory()->create();

    $first = app(SubscribeResellerAction::class)->execute($reseller, Plan::factory()->trialDays(14)->create());
    $second = app(SubscribeResellerAction::class)->execute($reseller, Plan::factory()->trialDays(14)->create());

    expect($first->trial_ends_at)->not->toBeNull()
        ->and($first->isOnTrial())->toBeTrue()
        ->and($second->trial_ends_at)->toBeNull();
});

it('resolves plan and reseller feature entitlements', function (): void {
    $reseller = Reseller::factory()->create();
    $plan = Plan::factory()->withFeatures(['custom_domain'])->create();
    Subscription::factory()->forSubscriber($reseller)->for($plan)->create();

    expect($plan->allows('custom_domain'))->toBeTrue()
        ->and($plan->allows('priority_support'))->toBeFalse()
        ->and($reseller->allows('custom_domain'))->toBeTrue()
        ->and($reseller->allows('priority_support'))->toBeFalse();
});

it('seeds the default plans idempotently', function (): void {
    (new PlanSeeder())->run();
    (new PlanSeeder())->run();

    expect(Plan::query()->count())->toBe(3)
        ->and(Plan::query()->where('slug', 'pro')->sole()->allows('priority_support'))->toBeTrue();
});
