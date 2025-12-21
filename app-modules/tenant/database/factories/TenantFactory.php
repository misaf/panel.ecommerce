<?php

declare(strict_types=1);

namespace Misaf\Tenant\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\Tenant\Models\Tenant;

/**
 * @extends Factory<Tenant>
 */
final class TenantFactory extends Factory
{
    /**
     * @var class-string<Tenant>
     */
    protected $model = Tenant::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => fake()->unique()->sentence(3),
            'description' => fake()->text(),
            'slug'        => fn(array $attributes) => Str::slug($attributes['name']),
            'status'      => fake()->boolean(),
        ];
    }
}
