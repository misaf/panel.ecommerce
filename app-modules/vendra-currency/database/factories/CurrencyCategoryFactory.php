<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Misaf\VendraCurrency\Models\CurrencyCategory;
use Misaf\VendraSupport\Support\TenantAwareness;

/**
 * @extends Factory<CurrencyCategory>
 */
#[UseModel(CurrencyCategory::class)]
final class CurrencyCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => fake()->sentences(1, true),
            'description' => fake()->realTextBetween(100, 200),
            'slug'        => fn(array $attributes) => Str::slug($attributes['name']),
            'status'      => fake()->boolean(80),
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

    public function enabled(): static
    {
        return $this->state(fn(): array => ['status' => true]);
    }

    public function disabled(): static
    {
        return $this->state(fn(): array => ['status' => false]);
    }
}
