<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Filament\Resources\Carts\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class CartTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('vendra-cart::attributes.id'))
                    ->sortable(),

                TextColumn::make('token')
                    ->copyable()
                    ->label(__('vendra-cart::attributes.token'))
                    ->searchable(),

                TextColumn::make('owner_label')
                    ->label(__('vendra-cart::attributes.owner'))
                    ->placeholder('—'),

                TextColumn::make('items_count')
                    ->badge()
                    ->counts('items')
                    ->label(__('vendra-cart::attributes.items')),

                TextColumn::make('expires_at')
                    ->alignCenter()
                    ->badge()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-cart::attributes.expires_at'))
                    ->placeholder('—')
                    ->sinceTooltip()
                    ->sortable()
                    ->unless(
                        app()->isLocale('fa'),
                        fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                        fn(TextColumn $column) => $column->dateTime('Y-m-d H:i'),
                    ),

                TextColumn::make('created_at')
                    ->alignCenter()
                    ->badge()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-cart::attributes.created_at'))
                    ->sinceTooltip()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->unless(
                        app()->isLocale('fa'),
                        fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                        fn(TextColumn $column) => $column->dateTime('Y-m-d H:i'),
                    ),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),

                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->with('owner'))
            ->defaultSort('created_at', 'desc');
    }
}
