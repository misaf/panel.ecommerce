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
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Misaf\VendraSupport\Context\RequestJobContext;
use Misaf\VendraSupport\Contracts\SubscriptionCharger;
use Misaf\VendraSupport\Tenancy\TenantTableRegistry;
use Misaf\VendraUser\Models\User;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Authenticatable::class, User::class);
        $this->app->bind(ResetPassword::class, ResetPasswordNotification::class);
        $this->app->bind(VerifyEmail::class, VerifyEmailNotification::class);

        // vendra-support owns the SubscriptionCharger contract and its null default;
        // vendra-transaction stays unaware of subscription semantics. As the
        // composition root, the host app supplies the transaction-backed adapter
        // and binds it over the null charger.
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
        DB::prohibitDestructiveCommands(app()->isProduction());
        Password::defaults(fn() => Password::min(8));

        Event::listen(Authenticated::class, static function (Authenticated $event): void {
            $actorId = $event->user->getAuthIdentifier();

            (new RequestJobContext(
                actorId: is_int($actorId) || is_string($actorId) ? $actorId : null,
                actorType: $event->guard,
            ))->add();
        });

        Event::listen(Failed::class, static function (Failed $event): void {
            (new RequestJobContext(
                operation: 'auth_failed',
                actorType: $event->guard,
            ))->scope(static fn() => Log::warning('Authentication attempt failed.'));
        });

        Event::listen(Lockout::class, static function (Lockout $event): void {
            (new RequestJobContext(operation: 'auth_lockout'))
                ->scope(static fn() => Log::warning('Authentication lockout triggered.'));
        });

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
