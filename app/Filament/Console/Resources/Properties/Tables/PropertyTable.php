<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Properties\Tables;

use App\Filament\Console\Resources\Properties\Actions\ReplaceDomainAction;
use App\Models\Reseller;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Misaf\VendraTenant\Models\Tenant;

final class PropertyTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('console.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('reseller')
                    ->label(__('console.reseller'))
                    ->state(fn(Tenant $record): ?string => Reseller::query()->find($record->reseller_id)?->name)
                    ->placeholder('—'),

                TextColumn::make('domain')
                    ->label(__('console.domain'))
                    ->state(fn(Tenant $record): ?string => $record->activeDomainName())
                    ->placeholder('—'),

                TextColumn::make('admin_access')
                    ->label(__('console.admin_url'))
                    ->state(fn(Tenant $record): string => 'https://' . $record->slug . '.' . config('vendra-tenant.central_host'))
                    ->description(fn(Tenant $record): ?string => $record->activeDomainName()
                        ? 'https://admin.' . $record->activeDomainName()
                        : null)
                    ->url(fn(Tenant $record): string => 'https://' . $record->slug . '.' . config('vendra-tenant.central_host'))
                    ->openUrlInNewTab()
                    ->copyable()
                    ->copyMessage('URL copied')
                    ->placeholder('—'),

                IconColumn::make('status')
                    ->label(__('console.status'))
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ReplaceDomainAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
