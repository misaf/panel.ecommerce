<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Reseller;
use App\Models\ResellerUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Misaf\VendraSubscription\Actions\SubscribeAction;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;

final class CreateResellerAction
{
    public function __construct(
        private readonly CreateResellerOwnerAction $createResellerOwnerAction,
        private readonly SubscribeAction $subscribeAction,
    ) {}

    /**
     * Create a billing reseller and subscribe it to the given plan.
     *
     * @return array{reseller: Reseller, owner: ResellerUser, subscription: Subscription}
     */
    public function execute(
        Plan $plan,
        string $username,
        string $email,
        string $password,
        ?Carbon $startsAt = null,
        bool $status = true,
        bool $emailVerified = true,
    ): array {
        return DB::transaction(function () use ($plan, $username, $email, $password, $startsAt, $status, $emailVerified): array {
            $reseller = Reseller::query()->create([
                'name'   => $username,
                'slug'   => $username,
                'status' => $status,
                'email'  => $email,
            ]);

            $owner = $this->createResellerOwnerAction->execute(
                $reseller,
                $username,
                $email,
                $password,
                $emailVerified,
            );

            $subscription = $this->subscribeAction->execute($reseller, $plan, $startsAt);

            return [
                'reseller'      => $reseller,
                'owner'         => $owner,
                'subscription'  => $subscription,
            ];
        });
    }
}
