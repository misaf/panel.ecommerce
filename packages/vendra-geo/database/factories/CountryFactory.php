<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Misaf\VendraGeo\Models\Country;
use Misaf\VendraSupport\Support\TenantAwareness;

/**
 * @extends Factory<Country>
 */
#[UseModel(Country::class)]
final class CountryFactory extends Factory
{
    public function definition(): array
    {
        $country = fake()->unique()->country();

        return [
            'name'          => $country,
            'slug'          => Str::slug($country),
            'iso2'          => fake()->unique()->lexify('??'),
            'iso3'          => fake()->unique()->lexify('???'),
            'numeric_code'  => fake()->numerify('###'),
            'phone_code'    => '+' . fake()->numberBetween(1, 999),
            'currency_code' => fake()->currencyCode(),
            'latitude'      => fake()->latitude(),
            'longitude'     => fake()->longitude(),
            'status'        => fake()->boolean(80),
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
