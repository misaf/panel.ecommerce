<?php

declare(strict_types=1);

namespace Misaf\Geographical\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\Geographical\Models\GeographicalCity;
use Misaf\Geographical\Models\GeographicalNeighborhood;

/**
 * @extends Factory<GeographicalNeighborhood>
 */
final class GeographicalNeighborhoodFactory extends Factory
{
    /**
     * @var class-string<GeographicalNeighborhood>
     */
    protected $model = GeographicalNeighborhood::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'geographical_city_id' => GeographicalCity::factory(),
            'name'                 => fake()->unique()->sentence(3),
            'description'          => fake()->paragraph(),
            'slug'                 => fn(array $attributes) => Str::slug($attributes['name']),
            'status'               => fake()->boolean(),
        ];
    }
}
