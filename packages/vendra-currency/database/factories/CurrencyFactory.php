<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Misaf\VendraCurrency\Models\Currency;
use Misaf\VendraCurrency\Models\CurrencyCategory;
use Misaf\VendraSupport\Support\TenantAwareness;

/**
 * @extends Factory<Currency>
 */
#[UseModel(Currency::class)]
final class CurrencyFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->sentence();

        return [
            'currency_category_id' => CurrencyCategory::factory(),
            'name'                 => $name,
            'description'          => fake()->realTextBetween(100, 200),
            'slug'                 => Str::slug($name),
            'iso_code'             => fake()->unique()->currencyCode(),
            'conversion_rate'      => fake()->randomFloat(2, 0.1, 10),
            'decimal_place'        => fake()->numberBetween(0, 4),
            'is_default'           => fake()->boolean(1),
            'buy_price'            => fake()->numberBetween(70000, 100000),
            'sell_price'           => fake()->numberBetween(70000, 100000),
            'status'               => fake()->boolean(80),
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

    public function forCategory(CurrencyCategory $currencyCategory): static
    {
        return $this->state(fn(): array => [
            'currency_category_id' => $currencyCategory->id,
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
