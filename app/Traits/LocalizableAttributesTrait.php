<?php

declare(strict_types=1);

namespace App\Traits;

trait LocalizableAttributesTrait
{
    private function getLocalizedAttribute(string $attribute, ?string $locale): string|array
    {
        return $locale ? $this->getTranslation($attribute, $locale) : $this->getTranslations($attribute);
    }
}
