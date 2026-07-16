<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Contracts;

interface NamespacedLanguageLine
{
    /**
     * @return array<mixed>
     */
    public static function getTranslationsForGroup(
        string $locale,
        string $group,
        ?string $namespace = null,
    ): array;
}
