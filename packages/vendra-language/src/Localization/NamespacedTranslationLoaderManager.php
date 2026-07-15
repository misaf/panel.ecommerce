<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Localization;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraLanguage\Models\LanguageLine;
use Spatie\TranslationLoader\TranslationLoaderManager;

final class NamespacedTranslationLoaderManager extends TranslationLoaderManager
{
    /**
     * Load file translations and apply database overrides for package namespaces.
     *
     * @param string $locale
     * @param string $group
     * @param string|null $namespace
     *
     * @return array<mixed>
     */
    public function load($locale, $group, $namespace = null): array
    {
        $fileTranslations = parent::load($locale, $group, $namespace);

        if ( ! is_string($namespace) || '' === $namespace || '*' === $namespace) {
            return $fileTranslations;
        }

        try {
            return array_replace_recursive(
                $fileTranslations,
                LanguageLine::getTranslationsForGroup($locale, $group, $namespace),
            );
        } catch (QueryException $exception) {
            if ( ! Schema::hasTable((new LanguageLine())->getTable())) {
                return $fileTranslations;
            }

            throw $exception;
        }
    }
}
