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
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Misaf\VendraAttribute\Models\Attribute;
use Misaf\VendraSupport\Support\TagIntegration;

final class AttributeTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex()->sortable(['id']),

                TextColumn::make('name')
                    ->description(fn(Attribute $record): ?string => $record->description)
                    ->label(__('vendra-attribute::attributes.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('unit')
                    ->badge()
                    ->label(__('vendra-attribute::attributes.unit')),

                ...self::tagColumns(),

                TextColumn::make('values_count')
                    ->badge()
                    ->counts('values')
                    ->label(__('vendra-attribute::attributes.values')),

                ToggleColumn::make('status')
                    ->label(__('vendra-attribute::attributes.status')),

                TextColumn::make('created_at')
                    ->alignCenter()
                    ->badge()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-attribute::attributes.created_at'))
                    ->sinceTooltip()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->when(
                        app()->isLocale('fa'),
                        fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                        fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')
                    ),

                TextColumn::make('updated_at')
                    ->alignCenter()
                    ->badge()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-attribute::attributes.updated_at'))
                    ->sinceTooltip()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->when(
                        app()->isLocale('fa'),
                        fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                        fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')
                    ),
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
            ->defaultSort(column: 'id', direction: 'desc')
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('name'),
                        TextConstraint::make('unit'),
                        BooleanConstraint::make('status'),
                        NumberConstraint::make('position'),
                    ]),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->reorderable('position', direction: 'desc');
    }

    /** @return list<TextColumn> */
    private static function tagColumns(): array
    {
        if ( ! TagIntegration::isAvailable()) {
            return [];
        }

        return [
            TextColumn::make('tags.name')
                ->badge()
                ->label(__('vendra-support::attributes.tags'))
                ->toggleable(),
        ];
    }
}
