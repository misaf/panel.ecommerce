<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Currencies\Resources\Currencies\RelationManagers;

use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Schemas\CurrencyForm;
use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Schemas\CurrencyTable;
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
        return __('currency::navigation.currency');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('currency::navigation.currency');
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
        return CurrencyForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return CurrencyTable::configure($table)
            ->headerActions([
                CreateAction::make(),
            ]);

    }
}
