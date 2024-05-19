<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Admin;
use App\Filament\Admin\Resources\Blog\BlogPostCategoryResource;
use App\Filament\Admin\Resources\Blog\BlogPostResource;
use App\Filament\Admin\Resources\Currency\CurrencyCategoryResource;
use App\Filament\Admin\Resources\Currency\CurrencyResource;
use App\Filament\Admin\Resources\Faq\FaqCategoryResource;
use App\Filament\Admin\Resources\Faq\FaqResource;
use App\Filament\Admin\Resources\Geographical\GeographicalCityResource;
use App\Filament\Admin\Resources\Geographical\GeographicalCountryResource;
use App\Filament\Admin\Resources\Geographical\GeographicalNeighborhoodResource;
use App\Filament\Admin\Resources\Geographical\GeographicalStateResource;
use App\Filament\Admin\Resources\Geographical\GeographicalZoneResource;
use App\Filament\Admin\Resources\Language\LanguageLineResource;
use App\Filament\Admin\Resources\Language\LanguageResource;
use App\Filament\Admin\Resources\Permission\PermissionResource;
use App\Filament\Admin\Resources\Permission\RoleResource;
use App\Filament\Admin\Resources\Product\ProductCategoryResource;
use App\Filament\Admin\Resources\Product\ProductResource;
use App\Filament\Admin\Resources\User\UserProfileBalanceResource;
use App\Filament\Admin\Resources\User\UserProfileDocumentResource;
use App\Filament\Admin\Resources\User\UserProfilePhoneResource;
use App\Filament\Admin\Resources\User\UserProfileResource;
use App\Filament\Admin\Resources\User\UserResource;
use App\Settings\GlobalSetting;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use Filament\FontProviders\LocalFontProvider;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Tables;
use Filament\Widgets;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

final class AdminPanelProvider extends PanelProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentAsset::register([
            Css::make('example-external-stylesheet', '//cdn.font-store.ir/yekan.css'),
        ]);

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch): void {
            $switch->locales(['fa', 'en'])
                ->visible(outsidePanels: true);
        });

        // PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch): void {
        //     $panelSwitch->modalHeading('Available Panels')->visible(fn(): bool => auth()->user()->hasAnyRole([
        //         'admin',
        //         'general_manager',
        //         'super_admin',
        //     ]))->renderHook('panels::global-search.after');
        // });

        Tables\Table::configureUsing(function (Tables\Table $table): void {
            $table->paginationPageOptions([10, 25, 50]);
        });

        Tables\Actions\ViewAction::configureUsing(function (Tables\Actions\ViewAction $viewAction): void {
            $viewAction->button();
        });

        Tables\Actions\EditAction::configureUsing(function (Tables\Actions\EditAction $editAction): void {
            $editAction->button();
        });

        Tables\Actions\DeleteAction::configureUsing(function (Tables\Actions\DeleteAction $deleteAction): void {
            $deleteAction->button();
        });
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            // ->brandLogo(asset(app(GlobalSetting::class)->site_sidebar_logo_light))
            // ->brandName(app(GlobalSetting::class)->site_title)
            // ->darkModeBrandLogo(asset(app(GlobalSetting::class)->site_sidebar_logo_dark))
            ->brandLogoHeight('2rem')
            ->default()
            ->id('admin')
            ->login()
            ->path(LaravelLocalization::setLocale() . '/admin')
            ->profile(isSimple: false)
            ->font(
                'yekan',
                url: asset('css/fonts.css'),
                provider: LocalFontProvider::class,
            )
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(fn(): string => __('navigation.product_management'))
                    ->icon('heroicon-o-building-storefront')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('navigation.blog_management'))
                    ->icon('heroicon-o-presentation-chart-line')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('navigation.currency_management'))
                    ->icon('heroicon-o-currency-dollar')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('navigation.faq_management'))
                    ->icon('heroicon-o-question-mark-circle')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('navigation.user_management'))
                    ->icon('heroicon-o-users')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('navigation.geographical_management'))
                    ->icon('heroicon-o-globe-europe-africa')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('navigation.report_management'))
                    ->icon('heroicon-o-bug-ant')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('navigation.setting_management'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),
            ])
            ->plugin(SpatieLaravelTranslatablePlugin::make()->defaultLocales(['fa', 'en']))
            ->discoverClusters(in: app_path('Filament/Admin/Clusters'), for: 'App\\Filament\\Admin\\Clusters')
            ->pages([
                Pages\Dashboard::class,
                Admin\Pages\ManageGlobalSetting::class,
            ])
            ->resources([
                BlogPostCategoryResource::class,
                BlogPostResource::class,
                CurrencyCategoryResource::class,
                CurrencyResource::class,
                FaqCategoryResource::class,
                FaqResource::class,
                GeographicalZoneResource::class,
                GeographicalCountryResource::class,
                GeographicalStateResource::class,
                GeographicalCityResource::class,
                GeographicalNeighborhoodResource::class,
                LanguageResource::class,
                LanguageLineResource::class,
                PermissionResource::class,
                RoleResource::class,
                ProductCategoryResource::class,
                ProductResource::class,
                UserResource::class,
                UserProfileResource::class,
                UserProfileBalanceResource::class,
                UserProfileDocumentResource::class,
                UserProfilePhoneResource::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
                Admin\Widgets\StatOverview::class,
                Admin\Widgets\LatestUserTable::class,
                Admin\Widgets\LatestUserProfileDocumentTable::class,
            ])
            ->middleware([
                \App\Http\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\Session\Middleware\AuthenticateSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \App\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \Filament\Http\Middleware\DisableBladeIconComponents::class,
                \Filament\Http\Middleware\DispatchServingFilamentEvent::class,
                \Spatie\Multitenancy\Http\Middleware\NeedsTenant::class,
                \Spatie\Multitenancy\Http\Middleware\EnsureValidTenantSession::class,
                \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
                \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
            ])
            ->authMiddleware([
                \Filament\Http\Middleware\Authenticate::class,
            ])
            ->databaseNotifications()
            ->databaseTransactions()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->spa()
            ->viteTheme('resources/css/filament/admin/theme.css');
    }
}
