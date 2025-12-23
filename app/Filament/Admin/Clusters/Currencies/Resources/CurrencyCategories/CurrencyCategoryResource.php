<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories;

use App\Filament\Admin\Clusters\Currencies\CurrenciesCluster;
use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\RelationManagers\CurrencyRelationManager;
use App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories\Pages\CreateCurrencyCategory;
use App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories\Pages\EditCurrencyCategory;
use App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories\Pages\ListCurrencyCategories;
use App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories\Pages\ViewCurrencyCategory;
use App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories\Schemas\CurrencyCategoryForm;
use App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories\Schemas\CurrencyCategoryTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Misaf\Currency\Models\CurrencyCategory;

final class CurrencyCategoryResource extends Resource
{
    protected static ?string $model = CurrencyCategory::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'categories';

    protected static ?string $cluster = CurrenciesCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('currency::navigation.currency_category');
    }

    public static function getModelLabel(): string
    {
        return __('currency::navigation.currency_category');
    }

    public static function getNavigationGroup(): string
    {
        return __('currency::navigation.currency_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('currency::navigation.currency_category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('currency::navigation.currency_category');
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
