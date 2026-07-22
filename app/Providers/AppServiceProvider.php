<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Reseller;
use App\Models\ResellerUser;
use App\Notifications\Auth\ResetPasswordNotification;
use App\Notifications\Auth\VerifyEmailNotification;
use App\Support\TransactionSubscriptionCharger;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use Filament\Auth\Notifications\ResetPassword;
use Filament\Auth\Notifications\VerifyEmail;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Misaf\VendraSupport\Contracts\SubscriptionCharger;
use Misaf\VendraSupport\Support\TenantTableRegistry;
use Misaf\VendraUser\Models\User;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Authenticatable::class, User::class);
        $this->app->bind(ResetPassword::class, ResetPasswordNotification::class);
        $this->app->bind(VerifyEmail::class, VerifyEmailNotification::class);
        $this->app->singleton(SubscriptionCharger::class, TransactionSubscriptionCharger::class);
    }

    public function boot(): void
    {
        Relation::morphMap([
            'reseller'      => Reseller::class,
            'reseller_user' => ResellerUser::class,
        ]);

        $settingsTable = Config::get('settings.repositories.database.table');

        $this->app->make(TenantTableRegistry::class)->register(
            is_string($settingsTable) ? $settingsTable : 'settings',
        );
        URL::forceScheme('https');
        Model::shouldBeStrict();
        // DB::prohibitDestructiveCommands(app()->isProduction());
        Password::defaults(fn() => Password::min(8)->max(15));

        $this->configureTableDefaults();
        $this->configurePanelSwitch();
    }

    private function configureTableDefaults(): void
    {
        Table::configureUsing(function (Table $table) {
            return $table
                ->paginationPageOptions([10, 25, 50])
                ->deferLoading()
                ->defaultNumberLocale('en');
        });

        DateTimePicker::configureUsing(function (DateTimePicker $dateTimePicker) {
            return $dateTimePicker
                ->firstDayOfWeek(6)
                ->when(
                    app()->isLocale('fa'),
                    fn(DateTimePicker $component): DateTimePicker => $component
                        ->jalali()
                        ->viewData(fn(DateTimePicker $component): array => [
                            'defaultFocusedDate' => $component->getDefaultFocusedDate(),
                        ]),
                )
                ->native(false);
        });

        DatePicker::configureUsing(function (DatePicker $datePicker) {
            return $datePicker
                ->closeOnDateSelection()
                ->displayFormat('Y-m-d');
        });
    }

    private function configurePanelSwitch(): void
    {
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            return $panelSwitch
                ->simple()
                ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER);
        });
    }
}
