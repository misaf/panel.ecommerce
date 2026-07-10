<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Misaf\VendraGeo\Models\City;
use Misaf\VendraGeo\Models\State;
use Misaf\VendraSupport\Support\TenantAwareness;

/**
 * @extends Factory<City>
 */
#[UseModel(City::class)]
final class CityFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->city();

        return [
            'state_id'   => State::factory(),
            'country_id' => fn(array $attributes) => State::query()->find($attributes['state_id'])->country_id,
            'name'       => ['en' => $name],
            'slug'       => ['en' => Str::slug($name)],
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

    public function forState(State $state): static
    {
        return $this->state(fn(): array => [
            'country_id' => $state->country_id,
            'state_id'   => $state->id,
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
