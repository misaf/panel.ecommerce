<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\ActivityLogs\ActivityLogs;

use App\Filament\Admin\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\UpdatedAtTextColumn;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

final class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'logs/activities';

    public static function getModelLabel(): string
    {
        return __('navigation.activity_log');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.report_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.activity_log');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.activity_log');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('log_name')
                    ->toggleable(),

                TextColumn::make('description')
                    ->toggleable(),

                TextColumn::make('subject_type')
                    ->toggleable(),

                TextColumn::make('subject_id')
                    ->toggleable(),

                TextColumn::make('causer_type')
                    ->toggleable(),

                TextColumn::make('causer_id')
                    ->toggleable(),

                TextColumn::make('properties'),

                TextColumn::make('event')
                    ->toggleable(),

                CreatedAtTextColumn::make('created_at'),

                UpdatedAtTextColumn::make('updated_at')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('log_name'),
                            TextConstraint::make('description'),
                            TextConstraint::make('subject_type'),
                            TextConstraint::make('event'),
                            DateConstraint::make('created_at')
                                ->label(__('form.created_at')),
                            DateConstraint::make('updated_at')
                                ->label(__('form.updated_at')),
                        ]),
                ],
                layout: FiltersLayout::AboveContentCollapsible,
            )
            ->poll('10s');
    }
}
