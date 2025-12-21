<?php

declare(strict_types=1);

namespace Misaf\Faq\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\Faq\Models\Faq;
use Misaf\Faq\Models\FaqCategory;

/**
 * @extends Factory<Faq>
 */
final class FaqFactory extends Factory
{
    /**
     * @var class-string<Faq>
     */
    protected $model = Faq::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'faq_category_id' => FaqCategory::factory(),
            'name'            => fake()->unique()->sentence(3),
            'description'     => fake()->paragraph(),
            'slug'            => fn(array $attributes) => Str::slug($attributes['name']),
            'status'          => fake()->boolean(),
        ];
    }
}
