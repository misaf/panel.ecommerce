<?php

declare(strict_types=1);

namespace Misaf\UserLevel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\Tenant\Models\Tenant;
use Misaf\UserLevel\Models\UserLevel;

/**
 * @extends Factory<UserLevel>
 */
final class UserLevelFactory extends Factory
{
    /**
     * @var class-string<UserLevel>
     */
    protected $model = UserLevel::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id'   => Tenant::factory(),
            'name'        => fake()->unique()->sentence(3),
            'description' => fake()->text(),
            'slug'        => fn(array $attributes) => Str::slug($attributes['name']),
            'min_points'  => fake()->numberBetween(100, 500),
            'status'      => fake()->boolean(),
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
}
