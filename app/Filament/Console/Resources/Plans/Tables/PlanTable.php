<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Plans\Tables;

use Awcodes\BadgeableColumn\Components\Badge;
use Awcodes\BadgeableColumn\Components\BadgeableColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Misaf\VendraSubscription\Models\Plan;

final class PlanTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                BadgeableColumn::make('name')
                    ->label(__('console.name'))
                    ->icon(Heroicon::Tag)
                    ->searchable()
                    ->sortable()
                    ->prefixBadges([
                        Badge::make('is_default')
                            ->label(__('console.is_default'))
                            ->color('success')
                            ->size(Size::ExtraSmall)
                            ->hidden(fn(Plan $record): bool => ! $record->is_default),
                    ]),

                TextColumn::make('max_units')
                    ->label(__('console.max_units'))
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('period')
                    ->label(__('console.period'))
                    ->state(fn(Plan $record): string => "{$record->period_count} {$record->period_unit->value}"),

                TextColumn::make('price')
                    ->label(__('console.price'))
                    ->state(fn(Plan $record): string => $record->isFree()
                        ? __('console.free')
                        : $record->price . ' ' . ($record->currency_code ?? '')),

                ToggleColumn::make('status')
                    ->label(__('console.status'))
                    ->onIcon(Heroicon::Bolt),

                TextColumn::make('created_at')
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->sinceTooltip()
                    ->sortable()
                    ->when(
                        app()->isLocale('fa'),
                        fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                        fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')
                    ),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()
                        ->hidden(fn(Plan $record): bool => $record->isInUse()),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
