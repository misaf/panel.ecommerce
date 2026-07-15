<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Observers;

use Misaf\VendraLanguage\Models\Language;

final class LanguageObserver
{
    public function saving(Language $language): void
    {
        if ( ! $language->is_default) {
            return;
        }

        Language::query()
            ->where('is_default', true)
            ->whereKeyNot($language->getKey())
            ->update(['is_default' => false]);
    }
}
