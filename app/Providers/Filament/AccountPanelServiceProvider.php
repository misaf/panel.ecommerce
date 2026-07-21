<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\FontProviders\SpatieGoogleFontProvider;
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
use Spatie\Multitenancy\Http\Middleware\EnsureValidTenantSession;
use Spatie\Multitenancy\Http\Middleware\NeedsTenant;

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
            ->brandLogo(asset('images/vendra-logo.svg'))
            ->brandLogoHeight('2rem')
            ->brandName('Vendra Account')
            ->darkModeBrandLogo(asset('images/vendra-logo-dark.svg'))
            ->databaseNotifications()
            ->databaseTransactions()
            ->discoverResources(app_path('Filament/Account/Resources'), 'App\\Filament\\Account\\Resources')
            ->discoverPages(app_path('Filament/Account/Pages'), 'App\\Filament\\Account\\Pages')
            ->discoverWidgets(app_path('Filament/Account/Widgets'), 'App\\Filament\\Account\\Widgets')
            ->pages([Dashboard::class])
            ->homeUrl('/dashboard')
            ->authGuard('web')
            ->authPasswordBroker('users')
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
                NeedsTenant::class,
                EnsureValidTenantSession::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->font(
                fn(): string => app()->isLocale('fa') ? 'Vazirmatn' : 'Google',
                provider: SpatieGoogleFontProvider::class,
            )
            ->path('/account')
            ->profile()
            ->spa(hasPrefetching: true)
            ->topNavigation();
    }
}
