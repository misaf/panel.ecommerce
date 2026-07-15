<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\VendraLanguage\Enums\LanguageLineGroupEnum;
use Misaf\VendraLanguage\Models\LanguageLine;

/**
 * @extends Factory<LanguageLine>
 */
#[UseModel(LanguageLine::class)]
final class LanguageLineFactory extends Factory
{
    public function definition(): array
    {
        /** @var LanguageLineGroupEnum $group */
        $group = $this->faker->randomElement(LanguageLineGroupEnum::cases());

        return [
            'namespace' => null,
            'group'     => $group->value,
            'key'       => fake()->word(),
            'text'      => ['en' => fake()->word()],
        ];
    }
}
