<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Localization;

use Illuminate\Http\Request;
use Misaf\VendraLocalization\Contracts\LocaleResolver;

final class LanguageSwitchLocaleResolver implements LocaleResolver
{
    public function resolve(Request $request): ?string
    {
        $locale = $request->hasSession()
            ? $request->session()->get('locale')
            : null;

        $locale ??= $request->cookie('filament_language_switch_locale');

        return is_string($locale) && '' !== $locale ? $locale : null;
    }
}
