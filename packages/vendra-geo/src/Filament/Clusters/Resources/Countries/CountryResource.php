<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\Countries;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Misaf\VendraGeo\Filament\Clusters\Resources\Countries\Pages\CreateCountry;
use Misaf\VendraGeo\Filament\Clusters\Resources\Countries\Pages\EditCountry;
use Misaf\VendraGeo\Filament\Clusters\Resources\Countries\Pages\ListCountries;
use Misaf\VendraGeo\Filament\Clusters\Resources\Countries\Pages\ViewCountry;
use Misaf\VendraGeo\Filament\Clusters\Resources\Countries\Schemas\CountryForm;
use Misaf\VendraGeo\Filament\Clusters\Resources\Countries\Tables\CountryTable;
use Misaf\VendraGeo\Models\Country;
use Misaf\VendraSupport\Filament\Clusters\LocalizationCluster;

final class CountryResource extends Resource
{
    use Translatable;

    protected static ?string $model = Country::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'countries';

    protected static ?string $cluster = LocalizationCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-geo::navigation.country');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-geo::navigation.country');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-geo::navigation.geo_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-geo::navigation.country');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-geo::navigation.country');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCountries::route('/'),
            'create' => CreateCountry::route('/create'),
            'view'   => ViewCountry::route('/{record}'),
            'edit'   => EditCountry::route('/{record}/edit'),
        ];
    }

    public static function getDefaultTranslatableLocale(): string
    {
        return app()->getLocale();
    }

    public static function form(Schema $schema): Schema
    {
        return CountryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CountryTable::configure($table);
    }
}
