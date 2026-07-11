<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\Auth\Login;
use Filament\Contracts\Plugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Config;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use Misaf\VendraPermission\Models\Role;
use Misaf\VendraUser\Models\User;
use Spatie\Multitenancy\Http\Middleware\EnsureValidTenantSession;
use Spatie\Multitenancy\Http\Middleware\NeedsTenant;

final class AdminPanelServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->databaseNotifications()
            ->databaseTransactions()
            ->discoverClusters(app_path('Filament/Admin/Clusters'), 'App\\Filament\\Admin\\Clusters')
            ->discoverPages(app_path('Filament/Admin/Pages'), 'App\\Filament\\Admin\\Pages')
            ->discoverResources(app_path('Filament/Admin/Resources'), 'App\\Filament\\Admin\\Resources')
            ->globalSearchFieldKeyBindingSuffix()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->homeUrl('/')
            ->login(Login::class)
            ->sidebarFullyCollapsibleOnDesktop()
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()->label(fn(): string => __('navigation.user_management'))->icon('heroicon-o-users')->collapsed(),
                NavigationGroup::make()->label(fn(): string => __('navigation.billing_management'))->icon('heroicon-o-credit-card')->collapsed(),
                NavigationGroup::make()->label(fn(): string => __('navigation.transaction_management'))->icon('heroicon-o-users')->collapsed(),
                NavigationGroup::make()->label(fn(): string => __('navigation.content_management'))->collapsed(),
                NavigationGroup::make()->label(fn(): string => __('navigation.report_management'))->icon('heroicon-o-bug-ant')->collapsed(),
                NavigationGroup::make()->label(fn(): string => __('navigation.setting_management'))->icon('heroicon-o-cog-6-tooth')->collapsed(),
            ])
            ->path('/admin')
            ->profile()
            ->spa(hasPrefetching: true)
            ->strictAuthorization()
            ->unsavedChangesAlerts()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->plugins($this->plugins());
    }

    /**
     * @return array<int, Plugin>
     */
    private function plugins(): array
    {
        $plugins = [
            SpatieTranslatablePlugin::make()
                ->defaultLocales(['en', 'fa', 'de']),
        ];

        $developerLoginsPlugin = 'DutchCodingCompany\\FilamentDeveloperLogins\\FilamentDeveloperLoginsPlugin';

        if (app()->environment('local') && class_exists($developerLoginsPlugin)) {
            $plugins[] = $developerLoginsPlugin::make()
                ->enabled(fn(): bool => $this->hasSuperAdminUser())
                ->users(function (): array {
                    $role = $this->superAdminRole();

                    if (null === $role) {
                        return [];
                    }

                    return $this->userModelClass()::query()
                        ->role($role)
                        ->pluck('email', 'username')
                        ->toArray();
                })
                ->modelClass($this->userModelClass());
        }

        return $plugins;
    }

    /**
     * @return class-string<User>
     */
    private function userModelClass(): string
    {
        return User::class;
    }

    private function hasSuperAdminUser(): bool
    {
        $role = $this->superAdminRole();

        if (null === $role) {
            return false;
        }

        return $this->userModelClass()::query()
            ->role($role)
            ->exists();
    }

    private function superAdminRole(): ?Role
    {
        return Role::query()
            ->where('name', $this->configuredSuperAdminRole())
            ->where('guard_name', $this->authGuardName())
            ->first();
    }

    private function configuredSuperAdminRole(): string
    {
        return Config::string('vendra-permission.super_admin_role');
    }

    private function authGuardName(): string
    {
        return Config::string('auth.defaults.guard');
    }
}
