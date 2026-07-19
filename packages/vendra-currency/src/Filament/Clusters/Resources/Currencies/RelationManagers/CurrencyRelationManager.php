<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\CurrencyResource;
use Misaf\VendraCurrency\Models\CurrencyCategory;

final class CurrencyRelationManager extends RelationManager
{
    protected static string $relationship = 'currencies';

    protected static bool $isBadgeDeferred = true;

    protected static bool $isLazy = false;

    public static function getModelLabel(): string
    {
        return __('vendra-currency::navigation.currency');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-currency::navigation.currencies');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-currency::navigation.currencies');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        if ( ! $ownerRecord instanceof CurrencyCategory) {
            return (string) Number::format(0);
        }

        return (string) Number::format($ownerRecord->currencies()->count());
    }

    public function form(Schema $schema): Schema
    {
        return CurrencyResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return CurrencyResource::table($table)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
