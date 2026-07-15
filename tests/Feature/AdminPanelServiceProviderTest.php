<?php

declare(strict_types=1);

use App\Providers\Filament\AdminPanelServiceProvider;
use Filament\Panel;

it('uses a compact sidebar width', function (): void {
    $panel = (new AdminPanelServiceProvider(app()))->panel(Panel::make());

    expect($panel->getSidebarWidth())->toBe('18rem');
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
