<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\Cities\Tables;

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
use Livewire\Component as Livewire;
use Misaf\VendraGeo\Models\City;
use Misaf\VendraGeo\Models\Country;
use Misaf\VendraGeo\Models\State;
use Misaf\VendraSupport\Filament\Concerns\InteractsWithTranslatedTableRecords;

final class CityTable
{
    use InteractsWithTranslatedTableRecords;

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

                TextColumn::make('state.name')
                    ->label(__('vendra-geo::attributes.state'))
                    ->searchable()
                    ->state(function (City $record, Livewire $livewire): string {
                        return $record->state
                            ? static::translatedAttribute($record->state, 'name', $livewire)
                            : '';
                    }),

                TextColumn::make('country.name')
                    ->label(__('vendra-geo::attributes.country'))
                    ->searchable()
                    ->state(function (City $record, Livewire $livewire): string {
                        return $record->country
                            ? static::translatedAttribute($record->country, 'name', $livewire)
                            : '';
                    }),

                ToggleColumn::make('status')
                    ->label(__('vendra-geo::attributes.status'))
                    ->onIcon('heroicon-m-bolt'),
            ])
            ->filters([
                SelectFilter::make('country_id')
                    ->relationship('country', 'name')
                    ->getOptionLabelFromRecordUsing(function (Country $record, Livewire $livewire): string {
                        return static::translatedAttribute($record, 'name', $livewire);
                    })
                    ->label(__('vendra-geo::attributes.country'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('state_id')
                    ->relationship('state', 'name')
                    ->getOptionLabelFromRecordUsing(function (State $record, Livewire $livewire): string {
                        return static::translatedAttribute($record, 'name', $livewire);
                    })
                    ->label(__('vendra-geo::attributes.state'))
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
