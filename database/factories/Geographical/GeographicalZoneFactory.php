<?php

declare(strict_types=1);

namespace Database\Factories\Geographical;

use App\Models\Geographical\GeographicalZone;
use Illuminate\Database\Eloquent\Factories\Factory;

final class GeographicalZoneFactory extends Factory
{
    protected $model = GeographicalZone::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'slug'        => $this->faker->slug(),
            'status'      => $this->faker->boolean(),
        ];
    }
}
