<?php

declare(strict_types=1);

namespace App\Filament\Reseller\Resources\Properties;

use App\Filament\Reseller\Resources\Properties\Pages\CreateProperty;
use App\Filament\Reseller\Resources\Properties\Pages\ListProperties;
use App\Filament\Reseller\Resources\Properties\Schemas\PropertyForm;
use App\Filament\Reseller\Resources\Properties\Tables\PropertyTable;
use App\Models\Reseller;
use App\Models\ResellerUser;
use BackedEnum;
use Filament\Facades\Filament;
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

    /**
     * The billing reseller of the currently authenticated owner.
     */
    public static function currentResellerId(): ?int
    {
        return self::currentReseller()?->id;
    }

    public static function currentReseller(): ?Reseller
    {
        $user = Filament::auth()->user();

        if ( ! $user instanceof ResellerUser) {
            return null;
        }

        return Reseller::query()->find($user->reseller_id);
    }

    public static function canCreate(): bool
    {
        $reseller = self::currentReseller();

        return null !== $reseller && $reseller->status;
    }

    public static function form(Schema $schema): Schema
    {
        return PropertyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PropertyTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListProperties::route('/'),
            'create' => CreateProperty::route('/create'),
        ];
    }
}
