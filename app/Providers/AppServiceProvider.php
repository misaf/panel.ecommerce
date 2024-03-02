<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contract\Language;
use App\Models\Language\Language as LanguageLanguage;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

final class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        FilamentAsset::register([
            Css::make('example-external-stylesheet', '//cdn.font-store.ir/yekan.css'),
        ]);

        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/livewire/update', $handle)
                ->middleware('web')
                ->prefix(LaravelLocalization::setLocale());
        });

        $this->app->bind(Language::class, LanguageLanguage::class);

        Model::shouldBeStrict( ! $this->app->environment('production'));

        URL::forceScheme('https');

        $this->app['request']->server->set('HTTPS', 'on');

        if ($this->app->environment('production')) {
            Password::defaults(fn() => Password::min(8)->mixedCase());
        }

        DB::listen(fn($query) => Log::info($query->sql, $query->bindings));

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch): void {
            $switch->locales(['fa', 'en'])
                ->visible(outsidePanels: true);
        });

        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch): void {
            $panelSwitch->modalHeading('Available Panels')->visible(fn(): bool => auth()->user()?->hasAnyRole([
                'admin',
                'general_manager',
                'super_admin',
            ]))->renderHook('panels::global-search.after');
        });

        // Lang::handleMissingKeysUsing(function (string $key, array $replacements, string $locale) {
        //     info("Missing translation key [{$key}] detected.");

        //     return $key;
        // });
    }

    public function register(): void {}
}
