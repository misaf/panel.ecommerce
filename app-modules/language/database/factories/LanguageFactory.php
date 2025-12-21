<?php

declare(strict_types=1);

namespace Misaf\Language\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\Language\Models\Language;

/**
 * @extends Factory<Language>
 */
final class LanguageFactory extends Factory
{
    /**
     * @var class-string<Language>
     */
    protected $model = Language::class;

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
