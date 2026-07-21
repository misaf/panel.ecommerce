<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraSubscription\Notifications\SubscriptionExpiringNotification;
use Misaf\VendraSubscription\Notifications\WebsitesSuspendedNotification;

final class EnforceSubscriptionsAction
{
    /**
     * Number of days before expiry that an owner is reminded to renew.
     */
    private const int EXPIRY_REMINDER_DAYS = 7;

    /**
     * Expire lapsed subscriptions, remind owners of soon-to-expire ones, and
     * suspend the websites of accounts whose subscription ended more than the
     * plan's grace window ago.
     *
     * @return array{expired: int, reminded: int, suspended_websites: int, suspended_accounts: int}
     */
    public function execute(): array
    {
        $expired = $this->expireLapsedSubscriptions();

        $reminded = $this->remindExpiringSubscriptions();

        [$suspendedWebsites, $suspendedAccounts] = $this->suspendPastGraceWebsites();

        return [
            'expired'            => $expired,
            'reminded'           => $reminded,
            'suspended_websites' => $suspendedWebsites,
            'suspended_accounts' => $suspendedAccounts,
        ];
    }

    private function expireLapsedSubscriptions(): int
    {
        return Subscription::query()
            ->lapsed()
            ->update(['status' => SubscriptionStatus::Expired->value]);
    }

    private function remindExpiringSubscriptions(): int
    {
        $reminded = 0;

        Subscription::query()
            ->expiringWithin(self::EXPIRY_REMINDER_DAYS)
            ->with('account')
            ->chunkById(100, function (Collection $subscriptions) use (&$reminded): void {
                /** @var Collection<int, Subscription> $subscriptions */
                foreach ($subscriptions as $subscription) {
                    $account = $subscription->account;

                    if (null !== $account && $account->hasOwnerContact()) {
                        $account->notify(new SubscriptionExpiringNotification($subscription));
                    }

                    $subscription->forceFill(['expiry_reminder_sent_at' => now()])->save();
                    $reminded++;
                }
            });

        return $reminded;
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function suspendPastGraceWebsites(): array
    {
        $suspendedWebsites = 0;
        $suspendedAccounts = 0;

        Account::query()
            ->whereHas('tenants', fn(Builder $query): Builder => $query->where('status', true))
            ->chunkById(100, function (Collection $accounts) use (&$suspendedWebsites, &$suspendedAccounts): void {
                /** @var Collection<int, Account> $accounts */
                foreach ($accounts as $account) {
                    if (null !== $account->activeSubscription()) {
                        continue;
                    }

                    $latest = $account->subscriptions()->latest('starts_at')->first();
                    $suspendAt = $latest?->suspendAt();

                    if (null === $suspendAt || $suspendAt->isFuture()) {
                        continue;
                    }

                    $count = $account->tenants()->where('status', true)->update(['status' => false]);

                    if ($count > 0) {
                        $suspendedWebsites += $count;
                        $suspendedAccounts++;

                        if ($account->hasOwnerContact()) {
                            $account->notify(new WebsitesSuspendedNotification($count));
                        }
                    }
                }
            });

        return [$suspendedWebsites, $suspendedAccounts];
    }
}
