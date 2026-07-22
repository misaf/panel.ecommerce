<?php

declare(strict_types=1);

use App\Actions\EnforceSubscriptionsAction;
use App\Models\Reseller;
use Illuminate\Support\Carbon;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraTenant\Models\Tenant;

/**
 * Create a reseller whose only subscription is active but past its end date.
 */
function lapsedReseller(int $graceDays, Carbon $endsAt): Reseller
{
    $reseller = Reseller::factory()->create();
    $plan = Plan::factory()->graceDays($graceDays)->create();

    Subscription::factory()->forSubscriber($reseller)->for($plan)->create([
        'status'    => SubscriptionStatus::Active,
        'starts_at' => now()->subMonths(2),
        'ends_at'   => $endsAt,
    ]);

    return $reseller;
}

it('expires subscriptions whose period has lapsed', function (): void {
    $reseller = lapsedReseller(graceDays: 0, endsAt: now()->subDay());
    $subscription = $reseller->subscriptions()->sole();

    app(EnforceSubscriptionsAction::class)->execute();

    expect($subscription->refresh()->status)->toBe(SubscriptionStatus::Expired);
});

it('suspends properties once the grace period has passed', function (): void {
    $reseller = lapsedReseller(graceDays: 0, endsAt: now()->subDays(2));
    $property = Tenant::factory()->create(['reseller_id' => $reseller->getKey(), 'status' => true]);

    $result = app(EnforceSubscriptionsAction::class)->execute();

    expect($property->refresh()->status)->toBeFalse()
        ->and($result['suspended_properties'])->toBe(1)
        ->and($result['suspended_resellers'])->toBe(1);
});

it('keeps properties live while still within the grace period', function (): void {
    $reseller = lapsedReseller(graceDays: 10, endsAt: now()->subDay());
    $property = Tenant::factory()->create(['reseller_id' => $reseller->getKey(), 'status' => true]);

    app(EnforceSubscriptionsAction::class)->execute();

    expect($property->refresh()->status)->toBeTrue();
});

it('leaves properties of resellers with an active subscription untouched', function (): void {
    $reseller = Reseller::factory()->create();
    Subscription::factory()->forSubscriber($reseller)->for(Plan::factory()->graceDays(0))->create();
    $property = Tenant::factory()->create(['reseller_id' => $reseller->getKey(), 'status' => true]);

    $result = app(EnforceSubscriptionsAction::class)->execute();

    expect($property->refresh()->status)->toBeTrue()
        ->and($result['suspended_properties'])->toBe(0);
});
