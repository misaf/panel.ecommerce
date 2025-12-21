<?php

declare(strict_types=1);

namespace Misaf\Faq\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\Faq\Models\FaqCategory;

/**
 * @extends Factory<FaqCategory>
 */
final class FaqCategoryFactory extends Factory
{
    /**
     * @var class-string<FaqCategory>
     */
    protected $model = FaqCategory::class;

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
