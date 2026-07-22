<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Resellers;

use App\Filament\Console\Resources\Resellers\Pages\CreateReseller;
use App\Filament\Console\Resources\Resellers\Pages\EditReseller;
use App\Filament\Console\Resources\Resellers\Pages\ListResellers;
use App\Filament\Console\Resources\Resellers\Schemas\ResellerForm;
use App\Filament\Console\Resources\Resellers\Tables\ResellerTable;
use App\Models\Reseller;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class ResellerResource extends Resource
{
    protected static ?string $model = Reseller::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $slug = 'resellers';

    public static function getModelLabel(): string
    {
        return __('console.reseller');
    }

    public static function getPluralModelLabel(): string
    {
        return __('console.resellers');
    }

    public static function getNavigationLabel(): string
    {
        return __('console.resellers');
    }

    public static function getNavigationGroup(): string
    {
        return __('console.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return ResellerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResellerTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListResellers::route('/'),
            'create' => CreateReseller::route('/create'),
            'edit'   => EditReseller::route('/{record}/edit'),
        ];
    }
}
