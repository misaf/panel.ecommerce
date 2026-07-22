<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Reseller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;

final class CreateResellerAction
{
    public function __construct(private readonly SubscribeResellerAction $subscribeResellerAction) {}

    /**
     * Create a billing reseller and subscribe it to the given plan.
     *
     * @return array{reseller: Reseller, subscription: Subscription}
     */
    public function execute(
        string $name,
        Plan $plan,
        ?Carbon $startsAt = null,
        ?string $ownerName = null,
        ?string $ownerEmail = null,
        bool $status = true,
    ): array {
        return DB::transaction(function () use ($name, $plan, $startsAt, $ownerName, $ownerEmail, $status): array {
            $reseller = Reseller::query()->create([
                'name'        => $name,
                'slug'        => $name,
                'status'      => $status,
                'owner_name'  => $ownerName,
                'owner_email' => $ownerEmail,
            ]);

            $subscription = $this->subscribeResellerAction->execute($reseller, $plan, $startsAt);

            return [
                'reseller'      => $reseller,
                'subscription'  => $subscription,
            ];
        });
    }
}
