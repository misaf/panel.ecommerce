<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\CurrencyResource;
use Misaf\VendraCurrency\Models\Currency;

final class CurrencyRelationManager extends RelationManager
{
    protected static string $relationship = 'currencies';

    protected static bool $isLazy = false;

    public static function getModelLabel(): string
    {
        return __('vendra-currency::navigation.currency');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-currency::navigation.currency');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        /** @var Collection<int, Currency> $currencies */
        $currencies = $ownerRecord->relationLoaded('currencies')
            ? $ownerRecord->getRelation('currencies')
            : $ownerRecord->newCollection();

        return (string) Number::format($currencies->count());
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
