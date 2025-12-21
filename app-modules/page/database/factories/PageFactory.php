<?php

declare(strict_types=1);

namespace Misaf\Page\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\Page\Models\Page;
use Misaf\Page\Models\PageCategory;

/**
 * @extends Factory<Page>
 */
final class PageFactory extends Factory
{
    /**
     * @var class-string<Page>
     */
    protected $model = Page::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'page_category_id' => PageCategory::factory(),
            'name'             => fake()->unique()->sentence(3),
            'description'      => fake()->text(),
            'slug'             => fn(array $attributes) => Str::slug($attributes['name']),
            'status'           => fake()->boolean(),
        ];
    }
}
