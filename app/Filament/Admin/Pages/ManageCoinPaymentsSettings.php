<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Clusters\Settings\SettingsCluster;
use Filament\Clusters\Cluster;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Misaf\Coinpayments\Settings\CoinPaymentsSettings;

final class ManageCoinPaymentsSettings extends SettingsPage
{
    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = SettingsCluster::class;

    protected static ?int $navigationSort = 2;

    protected static string $settings = CoinPaymentsSettings::class;

    protected static ?string $slug = 'coinpayments';

    public static function getModelLabel(): string
    {
        return __('payment.coinpayments');
    }

    public static function getNavigationGroup(): string
    {
        return __('payment.payments');
    }

    public static function getNavigationLabel(): string
    {
        return __('payment.coinpayments');
    }

    public static function getPluralModelLabel(): string
    {
        return __('payment.coinpayments');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('deposit')
                            ->label(__('payment.buy'))
                            ->schema([
                                TextInput::make('usd_x_toman')
                                    ->columnSpanFull()
                                    ->extraAttributes(['dir' => 'ltr'])
                                    ->label(__('vendra-transaction::attributes.amount'))
                                    ->numeric()
                                    ->required(),

                                TextInput::make('crazy_bonus_deposit_min_amount')
                                    ->columnSpanFull()
                                    ->extraAttributes(['dir' => 'ltr'])
                                    ->label(__('payment.crazy_bonus_minimum_amount'))
                                    ->numeric()
                                    ->required(),

                                TextInput::make('crazy_bonus_deposit_amount')
                                    ->columnSpanFull()
                                    ->extraAttributes(['dir' => 'ltr'])
                                    ->label(__('payment.crazy_bonus_amount'))
                                    ->numeric()
                                    ->required(),

                                Toggle::make('crazy_bonus_deposit_status')
                                    ->columnSpanFull()
                                    ->label(__('payment.crazy_bonus_deposit_status'))
                                    ->rules('required')
                                    ->default(false),
                            ]),

                        Tab::make('withdrawal')
                            ->label(__('payment.sell'))
                            ->schema([
                                TextInput::make('toman_x_usd')
                                    ->columnSpanFull()
                                    ->extraAttributes(['dir' => 'ltr'])
                                    ->label(__('vendra-transaction::attributes.amount'))
                                    ->numeric()
                                    ->required(),

                                TextInput::make('crazy_bonus_withdrawal_min_amount')
                                    ->columnSpanFull()
                                    ->extraAttributes(['dir' => 'ltr'])
                                    ->label(__('payment.crazy_bonus_minimum_amount'))
                                    ->numeric()
                                    ->required(),

                                TextInput::make('crazy_bonus_withdrawal_amount')
                                    ->columnSpanFull()
                                    ->extraAttributes(['dir' => 'ltr'])
                                    ->label(__('payment.crazy_bonus_amount'))
                                    ->numeric()
                                    ->required(),

                                Toggle::make('crazy_bonus_withdrawal_status')
                                    ->columnSpanFull()
                                    ->label(__('payment.crazy_bonus_withdrawal_status'))
                                    ->rules('required')
                                    ->default(false),

                                Fieldset::make('Label')
                                    ->label(__('payment.apply_limit'))
                                    ->schema([
                                        TextInput::make('withdrawal_limit_count')
                                            ->columnSpanFull()
                                            ->extraAttributes(['dir' => 'ltr'])
                                            ->label(__('payment.count'))
                                            ->numeric()
                                            ->required(),

                                        TextInput::make('withdrawal_limit_hours')
                                            ->columnSpanFull()
                                            ->extraAttributes(['dir' => 'ltr'])
                                            ->label(__('payment.duration_hours'))
                                            ->numeric()
                                            ->required(),
                                    ]),
                            ]),
                    ])
                    ->persistTabInQueryString('coinpayments-settings-tab')
                    ->columnSpanFull(),
            ]);
    }

    public function getTitle(): string|Htmlable
    {
        return __('payment.coinpayments');
    }
}
