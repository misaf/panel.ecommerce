<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Currencies\Resources\Currencies\RelationManagers;

use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\CurrencyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

final class CurrencyRelationManager extends RelationManager
{
    protected static string $relationship = 'currencies';

    public static function getModelLabel(): string
    {
        return __('navigation.currency');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.currency');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('navigation.currency');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
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
