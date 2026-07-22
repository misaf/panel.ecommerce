<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Properties;

use App\Filament\Console\Resources\Properties\Pages\CreateProperty;
use App\Filament\Console\Resources\Properties\Pages\EditProperty;
use App\Filament\Console\Resources\Properties\Pages\ListProperties;
use App\Filament\Console\Resources\Properties\RelationManagers\DomainsRelationManager;
use App\Filament\Console\Resources\Properties\Schemas\PropertyForm;
use App\Filament\Console\Resources\Properties\Tables\PropertyTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Misaf\VendraTenant\Models\Tenant;

final class PropertyResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $slug = 'properties';

    public static function getModelLabel(): string
    {
        return __('console.property');
    }

    public static function getPluralModelLabel(): string
    {
        return __('console.properties');
    }

    public static function getNavigationLabel(): string
    {
        return __('console.properties');
    }

    public static function getNavigationGroup(): string
    {
        return __('console.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return PropertyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PropertyTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DomainsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListProperties::route('/'),
            'create' => CreateProperty::route('/create'),
            'edit'   => EditProperty::route('/{record}/edit'),
        ];
    }
}
