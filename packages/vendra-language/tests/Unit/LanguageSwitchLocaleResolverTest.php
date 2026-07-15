<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Misaf\VendraLanguage\Localization\LanguageSwitchLocaleResolver;

it('resolves the locale selected in the language switch session', function (): void {
    $request = Request::create('/');
    $request->setLaravelSession(app('session')->driver());
    $request->session()->put('locale', 'de');

    expect((new LanguageSwitchLocaleResolver())->resolve($request))->toBe('de');
});

it('falls back to the language switch cookie', function (): void {
    $request = Request::create('/', cookies: [
        'filament_language_switch_locale' => 'fa',
    ]);

    expect((new LanguageSwitchLocaleResolver())->resolve($request))->toBe('fa');
});
