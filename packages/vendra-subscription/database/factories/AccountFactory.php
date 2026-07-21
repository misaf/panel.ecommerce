<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\VendraSubscription\Models\Account;

/**
 * @extends Factory<Account>
 */
#[UseModel(Account::class)]
final class AccountFactory extends Factory
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
