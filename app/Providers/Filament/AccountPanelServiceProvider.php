<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Misaf\VendraLocalization\Http\Middleware\SetLocale;

/**
 * The account (self-service) panel.
 *
 * An account owner manages their own billing account here: they see their
 * subscription and create/list websites within their plan's quota. It runs
 * outside the tenant middleware because an account spans multiple websites.
 */
final class AccountPanelServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('account')
            ->brandName('Vendra Account')
            ->databaseNotifications()
            ->databaseTransactions()
            ->discoverResources(app_path('Filament/Account/Resources'), 'App\\Filament\\Account\\Resources')
            ->discoverPages(app_path('Filament/Account/Pages'), 'App\\Filament\\Account\\Pages')
            ->discoverWidgets(app_path('Filament/Account/Widgets'), 'App\\Filament\\Account\\Widgets')
            ->pages([Dashboard::class])
            ->homeUrl('/account')
            ->login()
            ->maxContentWidth(Width::Full)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->path('/account')
            ->profile()
            ->sidebarCollapsibleOnDesktop()
            ->topNavigation();
    }
}
