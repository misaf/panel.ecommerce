<?php

declare(strict_types=1);

namespace Misaf\Geographical\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\Geographical\Models\GeographicalCountry;
use Misaf\Geographical\Models\GeographicalZone;

/**
 * @extends Factory<GeographicalCountry>
 */
final class GeographicalCountryFactory extends Factory
{
    /**
     * @var class-string<GeographicalCountry>
     */
    protected $model = GeographicalCountry::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'geographical_zone_id' => GeographicalZone::factory(),
            'name'                 => fake()->unique()->sentence(3),
            'description'          => fake()->paragraph(),
            'slug'                 => fn(array $attributes) => Str::slug($attributes['name']),
            'status'               => fake()->boolean(),
        ];
    }
}
