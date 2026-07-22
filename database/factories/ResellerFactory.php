<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Reseller;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Reseller>
 */
#[UseModel(Reseller::class)]
final class ResellerFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => fake()->unique()->company(),
            'description' => fake()->text(),
            'slug'        => fn(array $attributes) => Str::slug($attributes['name']),
            'status'      => true,
            'owner_name'  => fake()->name(),
            'owner_email' => fake()->unique()->safeEmail(),
        ];
    }

    public function withoutOwner(): static
    {
        return $this->state(fn(): array => [
            'owner_name'  => null,
            'owner_email' => null,
        ]);
    }

    public function enabled(): static
    {
        return $this->state(fn(): array => ['status' => true]);
    }

    public function disabled(): static
    {
        return $this->state(fn(): array => ['status' => false]);
    }
}
