<?php

declare(strict_types=1);

namespace Misaf\VendraActivityLog\Filament\Resources\Tables;

use Awcodes\BadgeableColumn\Components\Badge;
use Awcodes\BadgeableColumn\Components\BadgeableColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\Layout\Component as LayoutComponent;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Misaf\VendraActivityLog\Models\ActivityLog;

final class ActivityLogTable
{
    public static function configure(Table $table): Table
    {
        /**
         * @var array<int, Column|ColumnGroup|LayoutComponent> $columns
         */
        $columns = [
            TextColumn::make('row')
                ->label('#')
                ->rowIndex(),

            TextColumn::make('event')
                ->alignStart()
                ->badge()
                ->label(__('vendra-activity-log::tables.event')),

            BadgeableColumn::make('log_name')
                ->alignCenter()
                ->description(fn(ActivityLog $record): string => $record->description)
                ->icon(Heroicon::QueueList)
                ->label(__('vendra-activity-log::tables.log_name'))
                ->searchable()
                ->sortable(),

            BadgeableColumn::make('subject_type')
                ->alignStart()
                ->label(__('vendra-activity-log::tables.subject_type'))
                ->suffixBadges([
                    Badge::make('count')
                        ->label(fn(ActivityLog $record): string => Number::format((int) $record->subject_id) ?: '0')
                        ->size(Size::Small),
                ]),

            BadgeableColumn::make('causer_type')
                ->alignStart()
                ->label(__('vendra-activity-log::tables.causer_type'))
                ->suffixBadges([
                    Badge::make('count')
                        ->label(fn(ActivityLog $record): string => Number::format((int) $record->causer_id) ?: '0')
                        ->size(Size::Small),
                ]),

            TextColumn::make('batch_uuid')
                ->alignStart()
                ->label(__('vendra-activity-log::tables.batch_uuid')),

            TextColumn::make('created_at')
                ->alignCenter()
                ->badge()
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-activity-log::tables.created_at'))
                ->sinceTooltip()
                ->toggleable(isToggledHiddenByDefault: true)
                ->unless(
                    app()->isLocale('fa'),
                    fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                    fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')
                ),

            TextColumn::make('updated_at')
                ->alignCenter()
                ->badge()
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-activity-log::tables.updated_at'))
                ->sinceTooltip()
                ->toggleable(isToggledHiddenByDefault: true)
                ->unless(
                    app()->isLocale('fa'),
                    fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                    fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')
                ),
        ];

        return $table
            ->columns($columns)
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('event')
                                ->label(__('vendra-activity-log::attributes.event')),

                            TextConstraint::make('log_name')
                                ->label(__('vendra-activity-log::attributes.log_name')),

                            TextConstraint::make('subject_type')
                                ->label(__('vendra-activity-log::attributes.subject_type')),

                            TextConstraint::make('causer_type')
                                ->label(__('vendra-activity-log::attributes.causer_type')),

                            TextConstraint::make('batch_uuid')
                                ->label(__('vendra-activity-log::attributes.batch_uuid')),

                            DateConstraint::make('created_at')
                                ->label(__('vendra-activity-log::attributes.created_at')),

                            DateConstraint::make('updated_at')
                                ->label(__('vendra-activity-log::attributes.updated_at')),
                        ]),
                ],
                layout: FiltersLayout::AboveContentCollapsible,
            )
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                ]),
            ])
            ->defaultSort(column: 'id', direction: 'desc');
    }
}
