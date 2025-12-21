<?php

declare(strict_types=1);

namespace Misaf\Language\Facades;

use Illuminate\Support\Facades\Facade;
use Misaf\Language\Models\Language as LanguageModel;

/**
 * @method static array<string> getAvailableLocales()
 * @method static array<string> availableLocales()
 * @method static LanguageModel|null findByIsoCode(string $isoCode)
 * @method static string getDefaultLocale()
 * @method static void clearCache()
 */
final class LanguageService extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'language-service';
    }
}
