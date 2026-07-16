<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\Cities;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Misaf\VendraGeo\Filament\Clusters\Resources\Cities\Pages\CreateCity;
use Misaf\VendraGeo\Filament\Clusters\Resources\Cities\Pages\EditCity;
use Misaf\VendraGeo\Filament\Clusters\Resources\Cities\Pages\ListCities;
use Misaf\VendraGeo\Filament\Clusters\Resources\Cities\Pages\ViewCity;
use Misaf\VendraGeo\Filament\Clusters\Resources\Cities\Schemas\CityForm;
use Misaf\VendraGeo\Filament\Clusters\Resources\Cities\Tables\CityTable;
use Misaf\VendraGeo\Models\City;
use Misaf\VendraSupport\Filament\Clusters\LocalizationCluster;

final class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'cities';

    protected static ?string $cluster = LocalizationCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-geo::navigation.city');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-geo::navigation.city');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-geo::navigation.geo_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-geo::navigation.city');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-geo::navigation.city');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCities::route('/'),
            'create' => CreateCity::route('/create'),
            'view'   => ViewCity::route('/{record}'),
            'edit'   => EditCity::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return CityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CityTable::configure($table);
    }
}
