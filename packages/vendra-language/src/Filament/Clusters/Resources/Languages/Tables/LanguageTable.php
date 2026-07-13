<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Table;

final class LanguageTable
{
    public static function configure(Table $table): Table
    {
        /**
         * @var array<int, Column> $columns
         */
        $columns = [
            TextColumn::make('row')
                ->label('#')
                ->rowIndex(),

            SpatieMediaLibraryImageColumn::make('image')
                ->alignCenter()
                ->collection('languages')
                ->conversion('thumb-table')
                ->label(__('vendra-language::attributes.image'))
                ->stacked(),

            TextColumn::make('name')
                ->label(__('vendra-language::attributes.name'))
                ->searchable()
                ->sortable(),

            TextColumn::make('iso_code')
                ->badge()
                ->label(__('vendra-language::attributes.iso_code'))
                ->searchable()
                ->sortable(),

            ToggleColumn::make('is_default')
                ->label(__('vendra-language::attributes.is_default')),

            ToggleColumn::make('status')
                ->label(__('vendra-language::attributes.status'))
                ->onIcon('heroicon-m-bolt'),

            TextColumn::make('created_at')
                ->badge()
                ->dateTime('Y-m-d H:i')
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-language::attributes.created_at'))
                ->sinceTooltip()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->badge()
                ->dateTime('Y-m-d H:i')
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-language::attributes.updated_at'))
                ->sinceTooltip()
                ->toggleable(isToggledHiddenByDefault: true),
        ];

        return $table
            ->columns($columns)
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            BooleanConstraint::make('is_default')
                                ->label(__('vendra-language::attributes.is_default')),

                            BooleanConstraint::make('status')
                                ->label(__('vendra-language::attributes.status')),
                        ]),
                ],
                layout: FiltersLayout::AboveContentCollapsible,
            )
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
