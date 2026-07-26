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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use InvalidArgumentException;
use Misaf\VendraTenant\Models\Tenant;

final class PropertyResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $recordTitleAttribute = 'name';

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

        return null !== $reseller && $reseller->active;
    }

    public static function form(Schema $schema): Schema
    {
        return PropertyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PropertyTable::configure($table);
    }

    /**
     * @return array<int, string>
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug', 'domains.name'];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
            ->where('reseller_id', self::currentResellerId() ?? 0)
            ->with([
                'domains' => fn(Relation $relation): Relation => $relation->where('active', true),
            ]);
    }

    /**
     * @return array<string, string>
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $property = self::property($record);
        $domainName = $property->domains->pluck('name')->first();

        return [
            __('console.domain') => is_string($domainName) ? $domainName : '—',
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        $property = self::property($record);

        return static::getUrl('index', ['search' => $property->name]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListProperties::route('/'),
            'create' => CreateProperty::route('/create'),
        ];
    }

    private static function property(Model $record): Tenant
    {
        if ( ! $record instanceof Tenant) {
            throw new InvalidArgumentException('Property resources require a Tenant record.');
        }

        return $record;
    }
}
