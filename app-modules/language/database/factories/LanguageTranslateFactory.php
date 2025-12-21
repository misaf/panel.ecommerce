<?php

declare(strict_types=1);

namespace Misaf\Language\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\Language\Models\LanguageTranslate;

/**
 * @extends Factory<LanguageTranslate>
 */
final class LanguageTranslateFactory extends Factory
{
    /**
     * @var class-string<LanguageTranslate>
     */
    protected $model = LanguageTranslate::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'group' => fake()->word(),
            'key'   => fake()->word(),
            'text'  => fake()->sentence(),
        ];
    }
}
