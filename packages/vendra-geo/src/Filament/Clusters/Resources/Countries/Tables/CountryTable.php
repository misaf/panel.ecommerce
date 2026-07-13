<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\Countries\Tables;

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
use Filament\Tables\Table;

final class CountryTable
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

                TextColumn::make('iso2')
                    ->badge()
                    ->label(__('vendra-geo::attributes.iso2'))
                    ->searchable(),

                TextColumn::make('currency_code')
                    ->label(__('vendra-geo::attributes.currency_code'))
                    ->toggleable(),

                ToggleColumn::make('status')
                    ->label(__('vendra-geo::attributes.status'))
                    ->onIcon('heroicon-m-bolt'),
            ])
            ->filters([
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
