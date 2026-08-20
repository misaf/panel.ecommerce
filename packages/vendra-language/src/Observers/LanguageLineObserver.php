<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Observers;

use Misaf\VendraLanguage\Models\LanguageLine;

/**
 * Synchronous: the cache is flushed against the line's *original* attributes,
 * which only exist before the update is written.
 */
final class LanguageLineObserver
{
    public function updating(LanguageLine $languageLine): void
    {
        $languageLine->flushOriginalTranslationCache();
    }
}
