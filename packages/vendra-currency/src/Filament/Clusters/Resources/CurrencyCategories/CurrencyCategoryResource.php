<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\RelationManagers\CurrencyRelationManager;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages\CreateCurrencyCategory;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages\EditCurrencyCategory;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages\ListCurrencyCategories;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages\ViewCurrencyCategory;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Schemas\CurrencyCategoryForm;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Tables\CurrencyCategoryTable;
use Misaf\VendraCurrency\Models\CurrencyCategory;
use Misaf\VendraSupport\Filament\Clusters\SalesCluster;

use Misaf\VendraSupport\Filament\Navigation\NavigationPriority;

final class CurrencyCategoryResource extends Resource
{
    protected static ?string $model = CurrencyCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static ?int $navigationSort = NavigationPriority::CurrencyCategories->value;

    protected static ?string $slug = 'currency-categories';

    protected static ?string $cluster = SalesCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-currency::navigation.currency_category');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-currency::navigation.currency_category');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-currency::navigation.currency_categories');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-currency::navigation.currency_categories');
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
