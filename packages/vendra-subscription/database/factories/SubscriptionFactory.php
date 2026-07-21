<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;

/**
 * @extends Factory<Subscription>
 */
#[UseModel(Subscription::class)]
final class SubscriptionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'plan_id'    => Plan::factory(),
            'status'     => SubscriptionStatus::Active,
            'starts_at'  => now()->subDay(),
            'ends_at'    => now()->addMonth(),
        ];
    }

    public function expired(): static
    {
        return $this->state(fn(): array => [
            'status'    => SubscriptionStatus::Expired,
            'starts_at' => now()->subMonths(2),
            'ends_at'   => now()->subDay(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn(): array => ['status' => SubscriptionStatus::Cancelled]);
    }

    public function neverExpires(): static
    {
        return $this->state(fn(): array => ['ends_at' => null]);
    }
}
