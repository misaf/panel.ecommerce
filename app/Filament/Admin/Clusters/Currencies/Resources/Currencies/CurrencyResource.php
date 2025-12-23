<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Currencies\Resources\Currencies;

use App\Filament\Admin\Clusters\Currencies\CurrenciesCluster;
use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Pages\CreateCurrency;
use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Pages\EditCurrency;
use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Pages\ListCurrencies;
use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Pages\ViewCurrency;
use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Schemas\CurrencyForm;
use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Schemas\CurrencyTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Misaf\Currency\Models\Currency;

final class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'currencies';

    protected static ?string $cluster = CurrenciesCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('currency::navigation.currency');
    }

    public static function getModelLabel(): string
    {
        return __('currency::navigation.currency');
    }

    public static function getNavigationGroup(): string
    {
        return __('currency::navigation.currency_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('currency::navigation.currency');
    }

    public static function getPluralModelLabel(): string
    {
        return __('currency::navigation.currency');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCurrencies::route('/'),
            'create' => CreateCurrency::route('/create'),
            'view'   => ViewCurrency::route('/{record}'),
            'edit'   => EditCurrency::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return CurrencyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CurrencyTable::configure($table);
    }
}
