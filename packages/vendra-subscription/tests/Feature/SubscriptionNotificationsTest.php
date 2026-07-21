<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Notification;
use Misaf\VendraSubscription\Actions\EnforceSubscriptionsAction;
use Misaf\VendraSubscription\Actions\SubscribeAccountAction;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraSubscription\Notifications\SubscriptionActivatedNotification;
use Misaf\VendraSubscription\Notifications\SubscriptionExpiringNotification;
use Misaf\VendraSubscription\Notifications\WebsitesSuspendedNotification;
use Misaf\VendraTenant\Models\Tenant;

it('notifies the owner when a subscription is activated', function (): void {
    Notification::fake();

    $account = Account::factory()->create();

    app(SubscribeAccountAction::class)->execute($account, Plan::factory()->create());

    Notification::assertSentTo($account, SubscriptionActivatedNotification::class);
});

it('does not notify an account without an owner contact', function (): void {
    Notification::fake();

    $account = Account::factory()->withoutOwner()->create();

    app(SubscribeAccountAction::class)->execute($account, Plan::factory()->create());

    Notification::assertNothingSent();
});

it('reminds the owner once about a soon-to-expire subscription', function (): void {
    Notification::fake();

    $account = Account::factory()->create();
    $subscription = Subscription::factory()->for($account)->for(Plan::factory())->create([
        'status'    => SubscriptionStatus::Active,
        'starts_at' => now()->subDays(20),
        'ends_at'   => now()->addDays(3),
    ]);

    app(EnforceSubscriptionsAction::class)->execute();
    app(EnforceSubscriptionsAction::class)->execute();

    Notification::assertSentToTimes($account, SubscriptionExpiringNotification::class, 1);
    expect($subscription->refresh()->expiry_reminder_sent_at)->not->toBeNull();
});

it('notifies the owner when websites are suspended', function (): void {
    Notification::fake();

    $account = Account::factory()->create();
    Subscription::factory()->for($account)->for(Plan::factory()->graceDays(0))->create([
        'status'    => SubscriptionStatus::Active,
        'starts_at' => now()->subMonths(2),
        'ends_at'   => now()->subDays(2),
    ]);
    Tenant::factory()->create(['account_id' => $account->getKey(), 'status' => true]);

    app(EnforceSubscriptionsAction::class)->execute();

    Notification::assertSentTo($account, WebsitesSuspendedNotification::class);
});
