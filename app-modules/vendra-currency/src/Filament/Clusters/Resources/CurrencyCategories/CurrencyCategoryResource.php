<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Misaf\VendraCurrency\Filament\Clusters\CurrenciesCluster;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\RelationManagers\CurrencyRelationManager;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages\CreateCurrencyCategory;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages\EditCurrencyCategory;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages\ListCurrencyCategories;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages\ViewCurrencyCategory;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Schemas\CurrencyCategoryForm;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Tables\CurrencyCategoryTable;
use Misaf\VendraCurrency\Models\CurrencyCategory;

final class CurrencyCategoryResource extends Resource
{
    protected static ?string $model = CurrencyCategory::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'categories';

    protected static ?string $cluster = CurrenciesCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-currency::navigation.currency_category');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-currency::navigation.currency_category');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-currency::navigation.currency_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-currency::navigation.currency_category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-currency::navigation.currency_category');
    }

    public static function getRelations(): array
    {
        return [
            CurrencyRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCurrencyCategories::route('/'),
            'create' => CreateCurrencyCategory::route('/create'),
            'view'   => ViewCurrencyCategory::route('/{record}'),
            'edit'   => EditCurrencyCategory::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return CurrencyCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CurrencyCategoryTable::configure($table);
    }
}
