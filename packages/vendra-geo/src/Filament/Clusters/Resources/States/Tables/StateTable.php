<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\States\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class StateTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('name')
                    ->label(__('vendra-geo::attributes.name'))
                    ->searchable(),

                TextColumn::make('country.name')
                    ->label(__('vendra-geo::attributes.country'))
                    ->searchable(),

                TextColumn::make('code')
                    ->label(__('vendra-geo::attributes.code'))
                    ->toggleable(),

                TextColumn::make('type')
                    ->badge()
                    ->label(__('vendra-geo::attributes.type')),

                ToggleColumn::make('status')
                    ->label(__('vendra-geo::attributes.status'))
                    ->onIcon('heroicon-m-bolt'),
            ])
            ->filters([
                SelectFilter::make('country_id')
                    ->relationship('country', 'name')
                    ->label(__('vendra-geo::attributes.country'))
                    ->searchable()
                    ->preload(),

                QueryBuilder::make()
                    ->constraints([
                        BooleanConstraint::make('status')
                            ->label(__('vendra-geo::attributes.status')),
                    ]),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort(column: 'position', direction: 'desc')
            ->reorderable(column: 'position', direction: 'desc');
    }
}
