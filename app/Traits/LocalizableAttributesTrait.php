<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Arr;

trait LocalizableAttributesTrait
{
    /**
     * @param string $attribute
     * @param string|null $locale
     * @return string|array
     */
    private function getLocalizedAttribute(string $attribute, ?string $locale): string|array
    {
        return $locale ? $this->getTranslation($attribute, $locale) : Arr::camelize($this->getTranslations($attribute));
    }
}
