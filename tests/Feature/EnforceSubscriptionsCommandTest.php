<?php

declare(strict_types=1);

use App\Models\Reseller;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraTenant\Models\Tenant;

it('expires subscriptions and suspends properties via the command', function (): void {
    $reseller = Reseller::factory()->create();
    Subscription::factory()->forSubscriber($reseller)->for(Plan::factory()->graceDays(0))->create([
        'status'    => SubscriptionStatus::Active,
        'starts_at' => now()->subMonths(2),
        'ends_at'   => now()->subDays(2),
    ]);
    $property = Tenant::factory()->create(['reseller_id' => $reseller->getKey(), 'active' => true]);

    $this->artisan('vendra-subscription:enforce-subscriptions')->assertSuccessful();

    expect($property->refresh()->active)->toBeFalse()
        ->and($reseller->subscriptions()->sole()->status)->toBe(SubscriptionStatus::Expired);
});
