<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\Affiliate\Models\Affiliate;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Models\User;

/**
 * @extends Factory<Affiliate>
 */
final class AffiliateFactory extends Factory
{
    /**
     * @var class-string<Affiliate>
     */
    protected $model = Affiliate::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id'          => Tenant::factory(),
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
     * @param Tenant $tenant
     * @return static
     */
    public function forTenant(Tenant $tenant): static
    {
        return $this->state(fn(): array => [
            'tenant_id' => $tenant->id,
        ]);
    }

    /**
     * @param User $user
     * @return static
     */
    public function forUser(User $user): static
    {
        return $this->state(fn(): array => [
            'user_id' => $user->id,
        ]);
    }
}
