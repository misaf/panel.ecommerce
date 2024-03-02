<?php

declare(strict_types=1);

namespace Database\Factories\Geographical;

use App\Models\Geographical\GeographicalCity;
use App\Models\Geographical\GeographicalNeighborhood;
use Illuminate\Database\Eloquent\Factories\Factory;

final class GeographicalNeighborhoodFactory extends Factory
{
    protected $model = GeographicalNeighborhood::class;

    public function definition(): array
    {
        return [
            'geographical_city_id' => GeographicalCity::factory(),
            'name'                 => $this->faker->sentence(),
            'description'          => $this->faker->paragraph(),
            'slug'                 => $this->faker->slug(),
            'status'               => $this->faker->boolean(),
        ];
    }
}
