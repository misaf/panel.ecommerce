<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class ClicksRelationManager extends RelationManager
{
    protected static string $relationship = 'clicks';

    protected static bool $isLazy = false;

    public static function getModelLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate_click');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-affiliate::navigation.affiliate_click');
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('ip_address')
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-affiliate::attributes.ip_address'))
                    ->searchable(),

                TextColumn::make('user_agent')
                    ->label(__('vendra-affiliate::attributes.user_agent'))
                    ->limit(40)
                    ->toggleable(),

                TextColumn::make('referer')
                    ->label(__('vendra-affiliate::attributes.referer'))
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->alignCenter()
                    ->badge()
                    ->dateTime('Y-m-d H:i')
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-affiliate::attributes.created_at'))
                    ->sinceTooltip(),
            ])
            ->defaultSort(column: 'created_at', direction: 'desc');
    }
}
