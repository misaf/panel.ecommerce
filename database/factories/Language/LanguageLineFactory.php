<?php

declare(strict_types=1);

namespace Database\Factories\Language;

use App\Models\Language\LanguageLine;
use Illuminate\Database\Eloquent\Factories\Factory;

final class LanguageLineFactory extends Factory
{
    protected $model = LanguageLine::class;

    public function definition(): array
    {
        return [
            'group' => $this->faker->word(),
            'key'   => $this->faker->word(),
            'text'  => $this->faker->sentence(),
        ];
    }
}
