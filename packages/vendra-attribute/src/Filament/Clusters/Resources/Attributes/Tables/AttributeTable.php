<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Misaf\VendraAttribute\Models\Attribute;

final class AttributeTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->description(fn(Attribute $record): ?string => $record->description)
                    ->label(trans_choice('vendra-attribute::attributes.name', 1))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('unit')
                    ->badge()
                    ->label(trans_choice('vendra-attribute::attributes.unit', 1)),

                TextColumn::make('values_count')
                    ->badge()
                    ->counts('values')
                    ->label(trans_choice('vendra-attribute::attributes.values', 1)),

                ToggleColumn::make('status')
                    ->label(trans_choice('vendra-attribute::attributes.status', 1)),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('position', 'desc')
            ->reorderable('position', direction: 'desc');
    }
}
