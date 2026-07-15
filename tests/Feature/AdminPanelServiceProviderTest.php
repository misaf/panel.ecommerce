<?php

declare(strict_types=1);

use App\Providers\Filament\AdminPanelServiceProvider;
use Filament\Panel;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;

it('uses a compact sidebar width', function (): void {
    $panel = (new AdminPanelServiceProvider(app()))->panel(Panel::make());

    expect($panel->getSidebarWidth())->toBe('18rem');
});

it('uses the Vendra logo in light and dark modes', function (): void {
    $panel = (new AdminPanelServiceProvider(app()))->panel(Panel::make());

    expect($panel->getBrandName())->toBe('Vendra')
        ->and($panel->getBrandLogo())->toBe(asset('images/vendra-logo.svg'))
        ->and($panel->getDarkModeBrandLogo())->toBe(asset('images/vendra-logo-dark.svg'))
        ->and($panel->getBrandLogoHeight())->toBe('2rem')
        ->and(public_path('images/vendra-logo.svg'))->toBeFile()
        ->and(public_path('images/vendra-logo-dark.svg'))->toBeFile();
});

it('uses the font matching the application locale', function (string $locale, string $font): void {
    app()->setLocale($locale);

    $panel = (new AdminPanelServiceProvider(app()))->panel(Panel::make());

    expect($panel->getFontFamily())->toBe($font);
})->with([
    'Persian' => ['fa', 'Vazirmatn'],
    'English' => ['en', 'Google'],
    'German'  => ['de', 'Google'],
]);

it('uses the Vendra Language catalog for translatable resources', function (): void {
    config()->set('vendra-language.locales', ['EN', 'pt_br']);

    $panel = (new AdminPanelServiceProvider(app()))->panel(Panel::make());
    $plugin = $panel->getPlugin('spatie-translatable');

    expect($plugin)->toBeInstanceOf(SpatieTranslatablePlugin::class)
        ->and($plugin->getDefaultLocales())->toBe(['en', 'pt-BR']);
});
