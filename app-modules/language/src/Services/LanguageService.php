<?php

declare(strict_types=1);

namespace Misaf\Language\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Misaf\Language\Models\Language;

final class LanguageService
{
    private const CACHE_TAG = 'newsletter';
    private const CACHE_KEY = 'available-locales';
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Get all available (active) locales
     *
     * @return array<string>
     */
    public function getAvailableLocales(): array
    {
        return $this->availableLocales();
    }

    /**
     * Retrieve available (active) locales with caching
     *
     * @return array<string>
     */
    public function availableLocales(): array
    {
        return Cache::tags(self::CACHE_TAG)
            ->remember(self::CACHE_KEY, self::CACHE_TTL, function (): array {
                return Language::where('status', true)
                    ->pluck('iso_code')
                    ->toArray();
            });
    }

    /**
     * Find a language by its ISO code
     *
     * @param string $isoCode
     * @return Language|null
     */
    public function findByIsoCode(string $isoCode): ?Language
    {
        return Language::query()
            ->where('iso_code', $isoCode)
            ->where('status', true)
            ->first();
    }

    /**
     * Get the default locale
     *
     * @return string
     */
    public function getDefaultLocale(): string
    {
        $defaultLanguage = Language::query()
            ->where('is_default', true)
            ->where('status', true)
            ->first();

        return $defaultLanguage?->iso_code ?? Config::string('app.locale', 'en');
    }

    /**
     * Clear the cached available locales
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::tags(self::CACHE_TAG)->forget(self::CACHE_KEY);
    }
}
