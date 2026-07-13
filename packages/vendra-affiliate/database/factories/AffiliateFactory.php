<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraSupport\Support\TenantAwareness;
use Misaf\VendraUser\Models\User;

/**
 * @extends Factory<Affiliate>
 */
#[UseModel(Affiliate::class)]
final class AffiliateFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'            => User::factory(),
            'name'               => fake()->unique()->sentence(3),
            'description'        => fake()->paragraph(),
            'slug'               => fn(array $attributes) => Str::slug($attributes['name']),
            'commission_percent' => fake()->randomElements([0, 5, 10, 15, 20, 30, 40, 50]),
            'is_processing'      => fake()->boolean(),
            'status'             => fake()->boolean(),
        ];
    }

    /**
     * No-op without a tenant provider, since there is no `tenant_id` column.
     */
    public function forTenant(Model|int $tenant): static
    {
        if ( ! TenantAwareness::enabled()) {
            return $this;
        }

        return $this->state(fn(): array => [
            'tenant_id' => $tenant instanceof Model ? $tenant->getKey() : $tenant,
        ]);
    }

    public function forUser(User $user): static
    {
        return $this->state(fn(): array => [
            'user_id' => $user->id,
        ]);
    }
}
