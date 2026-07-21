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
 * The platform (super-admin) panel.
 *
 * Runs outside the tenant middleware stack so a platform operator can manage
 * accounts, plans, subscriptions, and websites across every tenant.
 */
final class PlatformPanelServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('platform')
            ->brandName('Vendra Platform')
            ->databaseNotifications()
            ->databaseTransactions()
            ->discoverResources(app_path('Filament/Platform/Resources'), 'App\\Filament\\Platform\\Resources')
            ->discoverPages(app_path('Filament/Platform/Pages'), 'App\\Filament\\Platform\\Pages')
            ->discoverWidgets(app_path('Filament/Platform/Widgets'), 'App\\Filament\\Platform\\Widgets')
            ->pages([Dashboard::class])
            ->globalSearchFieldKeyBindingSuffix()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->homeUrl('/platform')
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
            ->path('/platform')
            ->profile()
            ->sidebarCollapsibleOnDesktop()
            ->topNavigation();
    }
}
