<?php

declare(strict_types=1);

namespace Misaf\Page\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\Page\Models\PageCategory;

/**
 * @extends Factory<PageCategory>
 */
final class PageCategoryFactory extends Factory
{
    /**
     * @var class-string<PageCategory>
     */
    protected $model = PageCategory::class;

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
