<?php

declare(strict_types=1);

namespace App\Filament\Reseller\Resources\Properties\Tables;

use App\Filament\Reseller\Resources\Properties\Actions\ReplaceDomainAction;
use App\Filament\Reseller\Resources\Properties\PropertyResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Misaf\VendraTenant\Models\Tenant;

final class PropertyTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('reseller_id', PropertyResource::currentResellerId()))
            ->columns([
                TextColumn::make('name')
                    ->label(__('console.name'))
                    ->searchable()
                    ->sortable(),

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
            ->recordActions([
                ActionGroup::make([
                    ReplaceDomainAction::make(),
                    DeleteAction::make()
                        ->authorize(fn(): bool => PropertyResource::canCreate()),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(fn(): bool => PropertyResource::canCreate()),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
