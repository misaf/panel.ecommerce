<?php

declare(strict_types=1);

namespace Database\Factories\Faq;

use App\Models\Faq\Faq;
use App\Models\Faq\FaqCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

final class FaqFactory extends Factory
{
    protected $model = Faq::class;

    public function definition(): array
    {
        return [
            'faq_category_id' => FaqCategory::factory(),
            'name'            => $this->faker->sentence(),
            'description'     => $this->faker->paragraph(),
            'slug'            => $this->faker->slug(),
            'status'          => $this->faker->boolean(),
        ];
    }
}
