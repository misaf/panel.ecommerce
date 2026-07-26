<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Plans\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Misaf\VendraSubscription\Models\Plan;

final class PlanTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('console.name'))
                    ->searchable()
                    ->sortable(),

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

                IconColumn::make('status')
                    ->label(__('console.status'))
                    ->boolean(),

                IconColumn::make('is_default')
                    ->label(__('console.is_default'))
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
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
