<?php

declare(strict_types=1);

namespace Misaf\Currency\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\Currency\Models\Currency;
use Misaf\Currency\Models\CurrencyCategory;

/**
 * @extends Factory<Currency>
 */
final class CurrencyFactory extends Factory
{
    /**
     * @var class-string<Currency>
     */
    protected $model = Currency::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'currency_category_id' => CurrencyCategory::factory(),
            'name'                 => fake()->unique()->sentence(3),
            'description'          => fake()->paragraph(),
            'slug'                 => fn(array $attributes) => Str::slug($attributes['name']),
            'iso_code'             => fake()->languageCode(),
            'is_default'           => fake()->boolean(),
            'buy_price'            => fake()->numberBetween(70000, 100000),
            'sell_price'           => fake()->numberBetween(70000, 100000),
            'status'               => fake()->boolean(),
        ];
    }
}
