<?php

declare(strict_types=1);

namespace Database\Factories\Faq;

use App\Models\Faq\FaqCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

final class FaqCategoryFactory extends Factory
{
    protected $model = FaqCategory::class;

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
