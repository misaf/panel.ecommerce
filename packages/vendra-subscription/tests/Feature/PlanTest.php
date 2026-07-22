<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;
use Misaf\VendraSubscription\Enums\PeriodUnit;
use Misaf\VendraSubscription\Exceptions\PlanInUseException;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;

it('reports whether it is free', function (): void {
    expect(Plan::factory()->priced(0)->create()->isFree())->toBeTrue()
        ->and(Plan::factory()->priced(1900)->create()->isFree())->toBeFalse();
});

it('reports whether it offers a trial', function (): void {
    expect(Plan::factory()->trialDays(14)->create()->hasTrial())->toBeTrue()
        ->and(Plan::factory()->trialDays(0)->create()->hasTrial())->toBeFalse();
});

it('grants only its listed feature entitlements', function (): void {
    $plan = Plan::factory()->withFeatures(['custom_domain'])->create();

    expect($plan->allows('custom_domain'))->toBeTrue()
        ->and($plan->allows('priority_support'))->toBeFalse();
});

it('resolves the period end and the grace-adjusted suspend date', function (): void {
    $plan = Plan::factory()->period(PeriodUnit::Month, 2)->graceDays(7)->create();
    $start = Carbon::parse('2026-01-01');

    expect($plan->resolveEndDate($start)->toDateString())->toBe('2026-03-01')
        ->and($plan->resolveSuspendDate(Carbon::parse('2026-03-01'))->toDateString())->toBe('2026-03-08');
});

it('filters by enabled and disabled status', function (): void {
    $enabled = Plan::factory()->create(['status' => true]);
    $disabled = Plan::factory()->create(['status' => false]);

    expect(Plan::query()->enabled()->pluck('id'))->toContain($enabled->id)->not->toContain($disabled->id)
        ->and(Plan::query()->disabled()->pluck('id'))->toContain($disabled->id)->not->toContain($enabled->id);
});

it('blocks deletion while any subscription references it, even a trashed one', function (): void {
    $plan = Plan::factory()->create();
    $subscription = Subscription::factory()->for($plan)->create();

    expect(fn(): bool => $plan->delete())->toThrow(PlanInUseException::class);

    $subscription->delete();

    expect($plan->isInUse())->toBeTrue()
        ->and(fn(): bool => $plan->delete())->toThrow(PlanInUseException::class);
});
