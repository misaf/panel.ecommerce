<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Misaf\VendraGeo\Models\Country;
use Misaf\VendraGeo\Models\State;
use Misaf\VendraSupport\Support\TenantAwareness;

/**
 * @extends Factory<State>
 */
#[UseModel(State::class)]
final class StateFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->city();

        return [
            'country_id' => Country::factory(),
            'name'       => $name,
            'slug'       => Str::slug($name),
            'code'       => fake()->optional()->lexify('???'),
            'type'       => fake()->randomElement(['state', 'province']),
            'latitude'   => fake()->latitude(),
            'longitude'  => fake()->longitude(),
            'status'     => fake()->boolean(80),
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

    public function forCountry(Country $country): static
    {
        return $this->state(fn(): array => [
            'country_id' => $country->id,
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
