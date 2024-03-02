<?php

declare(strict_types=1);

namespace Database\Factories\Page;

use App\Models\Page\PageCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

final class PageCategoryFactory extends Factory
{
    protected $model = PageCategory::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->name(),
            'description' => $this->faker->text(),
            'slug'        => $this->faker->slug(),
            'status'      => $this->faker->boolean(),
        ];
    }
}
