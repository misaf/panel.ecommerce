<?php

declare(strict_types=1);

namespace Misaf\Currency\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\Currency\Models\CurrencyCategory;

/**
 * @extends Factory<CurrencyCategory>
 */
final class CurrencyCategoryFactory extends Factory
{
    /**
     * @var class-string<CurrencyCategory>
     */
    protected $model = CurrencyCategory::class;

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
