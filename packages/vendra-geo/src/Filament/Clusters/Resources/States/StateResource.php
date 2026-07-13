<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\States;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Misaf\VendraGeo\Filament\Clusters\GeoCluster;
use Misaf\VendraGeo\Filament\Clusters\Resources\States\Pages\CreateState;
use Misaf\VendraGeo\Filament\Clusters\Resources\States\Pages\EditState;
use Misaf\VendraGeo\Filament\Clusters\Resources\States\Pages\ListStates;
use Misaf\VendraGeo\Filament\Clusters\Resources\States\Pages\ViewState;
use Misaf\VendraGeo\Filament\Clusters\Resources\States\Schemas\StateForm;
use Misaf\VendraGeo\Filament\Clusters\Resources\States\Tables\StateTable;
use Misaf\VendraGeo\Models\State;

final class StateResource extends Resource
{
    use Translatable;

    protected static ?string $model = State::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'states';

    protected static ?string $cluster = GeoCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-geo::navigation.state');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-geo::navigation.state');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-geo::navigation.geo_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-geo::navigation.state');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-geo::navigation.state');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListStates::route('/'),
            'create' => CreateState::route('/create'),
            'view'   => ViewState::route('/{record}'),
            'edit'   => EditState::route('/{record}/edit'),
        ];
    }

    public static function getDefaultTranslatableLocale(): string
    {
        return app()->getLocale();
    }

    public static function form(Schema $schema): Schema
    {
        return StateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StateTable::configure($table);
    }
}
