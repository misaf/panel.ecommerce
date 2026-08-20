<?php

declare(strict_types=1);

namespace Misaf\VendraStore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\VendraStore\Models\Store;
use Misaf\VendraTenant\Enums\TenantProvisioningStatus;

/**
 * @extends Factory<Store>
 */
#[UseModel(Store::class)]
final class StoreFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'                     => fake()->unique()->sentence(3),
            'description'              => fake()->text(),
            'slug'                     => fn(array $attributes) => Str::slug($attributes['name']),
            'active'                   => fake()->boolean(),
            'billing_suspended_at'     => null,
            'provisioning_status'      => TenantProvisioningStatus::Ready,
            'provisioning_should_seed' => false,
            'provisioned_at'           => now(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(): array => ['active' => true]);
    }

    public function inactive(): static
    {
        return $this->state(fn(): array => ['active' => false]);
    }
}
