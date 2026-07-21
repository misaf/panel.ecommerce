<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;

final class CreateAccountAction
{
    public function __construct(private readonly SubscribeAccountAction $subscribeAccountAction) {}

    /**
     * Create a billing account and subscribe it to the given plan.
     *
     * @return array{account: Account, subscription: Subscription}
     */
    public function execute(
        string $name,
        Plan $plan,
        ?Carbon $startsAt = null,
        ?string $ownerName = null,
        ?string $ownerEmail = null,
    ): array {
        return DB::transaction(function () use ($name, $plan, $startsAt, $ownerName, $ownerEmail): array {
            $account = Account::query()->create([
                'name'        => $name,
                'slug'        => $name,
                'status'      => true,
                'owner_name'  => $ownerName,
                'owner_email' => $ownerEmail,
            ]);

            $subscription = $this->subscribeAccountAction->execute($account, $plan, $startsAt);

            return [
                'account'      => $account,
                'subscription' => $subscription,
            ];
        });
    }
}
