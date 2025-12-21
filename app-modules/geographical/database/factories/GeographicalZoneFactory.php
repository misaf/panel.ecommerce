<?php

declare(strict_types=1);

namespace Misaf\Geographical\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\Geographical\Models\GeographicalZone;

/**
 * @extends Factory<GeographicalZone>
 */
final class GeographicalZoneFactory extends Factory
{
    /**
     * @var class-string<GeographicalZone>
     */
    protected $model = GeographicalZone::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => fake()->unique()->sentence(3),
            'description' => fake()->text(),
            'slug'        => fn(array $attributes) => Str::slug($attributes['name']),
            'status'      => fake()->boolean(),
        ];
    }
}
