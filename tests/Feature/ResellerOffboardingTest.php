<?php

declare(strict_types=1);

use App\Actions\SubscribeResellerAction;
use App\Exceptions\SubscriptionLimitException;
use App\Models\Reseller;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraTenant\Models\TenantDomain;

it('blocks changing to a plan that cannot hold the current properties', function (): void {
    $reseller = Reseller::factory()->create();
    Tenant::factory()->count(2)->create(['reseller_id' => $reseller->getKey()]);

    app(SubscribeResellerAction::class)->execute($reseller, Plan::factory()->maxUnits(1)->create());
})->throws(SubscriptionLimitException::class);

it('allows renewing the same plan while at capacity', function (): void {
    $reseller = Reseller::factory()->create();
    Tenant::factory()->count(2)->create(['reseller_id' => $reseller->getKey()]);

    $subscription = app(SubscribeResellerAction::class)->execute($reseller, Plan::factory()->maxUnits(2)->create());

    expect($subscription->isActive())->toBeTrue();
});

it('soft-deletes properties and cancels the subscription when a reseller is deleted', function (): void {
    $reseller = Reseller::factory()->create();
    $subscription = Subscription::factory()->forSubscriber($reseller)->for(Plan::factory())->create();
    $property = Tenant::factory()->create(['reseller_id' => $reseller->getKey(), 'status' => true]);
    $domain = TenantDomain::factory()->for($property)->create(['status' => true]);

    $reseller->delete();

    expect(Tenant::query()->whereKey($property->getKey())->exists())->toBeFalse()
        ->and(TenantDomain::query()->whereKey($domain->getKey())->exists())->toBeFalse()
        ->and($subscription->refresh()->status)->toBe(SubscriptionStatus::Cancelled)
        ->and($reseller->fresh()?->trashed())->toBeTrue();
});
