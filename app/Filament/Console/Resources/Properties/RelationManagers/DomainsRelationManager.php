<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Properties\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class DomainsRelationManager extends RelationManager
{
    protected static string $relationship = 'domains';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('console.domain_history');
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('console.domain'))
                    ->searchable(),

                IconColumn::make('status')
                    ->label(__('console.active'))
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label(__('console.created_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                TextColumn::make('deleted_at')
                    ->label(__('console.replaced_at'))
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->defaultSort('id', 'desc');
    }
}
