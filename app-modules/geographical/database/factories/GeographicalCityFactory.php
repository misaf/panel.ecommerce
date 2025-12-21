<?php

declare(strict_types=1);

namespace Misaf\Geographical\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\Geographical\Models\GeographicalCity;
use Misaf\Geographical\Models\GeographicalState;

/**
 * @extends Factory<GeographicalCity>
 */
final class GeographicalCityFactory extends Factory
{
    /**
     * @var class-string<GeographicalCity>
     */
    protected $model = GeographicalCity::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'geographical_state_id' => GeographicalState::factory(),
            'name'                  => fake()->unique()->sentence(3),
            'description'           => fake()->paragraph(),
            'slug'                  => fn(array $attributes) => Str::slug($attributes['name']),
            'status'                => fake()->boolean(),
        ];
    }
}
