<?php

declare(strict_types=1);

namespace Misaf\VendraApi\State\Concerns;

trait NormalizesResourceValues
{
    /**
     * @param array<array-key, mixed> $translations
     *
     * @return array<string, string>
     */
    protected function normalizeTranslations(array $translations): array
    {
        $normalizedTranslations = [];

        foreach ($translations as $locale => $translation) {
            if (is_string($locale) && is_string($translation)) {
                $normalizedTranslations[$locale] = $translation;
            }
        }

        return $normalizedTranslations;
    }
}
