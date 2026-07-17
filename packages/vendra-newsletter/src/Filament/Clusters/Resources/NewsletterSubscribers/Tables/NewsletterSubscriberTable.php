<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;

final class NewsletterSubscriberTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex()->sortable(['id']),

                TextColumn::make('email')
                    ->alignStart()
                    ->copyable()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-newsletter::attributes.email'))
                    ->searchable(),

                TextColumn::make('name')
                    ->alignStart()
                    ->label(__('vendra-newsletter::attributes.name'))
                    ->placeholder('—')
                    ->searchable(),

                IconColumn::make('subscribed')
                    ->alignCenter()
                    ->boolean()
                    ->label(__('vendra-newsletter::attributes.status'))
                    ->state(fn(NewsletterSubscriber $record): bool => $record->isSubscribed()),

                TextColumn::make('subscribed_at')
                    ->alignCenter()
                    ->badge()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-newsletter::attributes.subscribed_at'))
                    ->placeholder('—')
                    ->toggleable()
                    ->when(
                        app()->isLocale('fa'),
                        fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                        fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')
                    ),

                TextColumn::make('unsubscribed_at')
                    ->alignCenter()
                    ->badge()
                    ->color('danger')
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-newsletter::attributes.unsubscribed_at'))
                    ->placeholder('—')
                    ->toggleable()
                    ->when(
                        app()->isLocale('fa'),
                        fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                        fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')
                    ),

                TextColumn::make('created_at')
                    ->alignCenter()
                    ->badge()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-newsletter::attributes.created_at'))
                    ->sinceTooltip()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->when(
                        app()->isLocale('fa'),
                        fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                        fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')
                    ),
            ])
            ->filters(
                [
                    TernaryFilter::make('unsubscribed_at')
                        ->label(__('vendra-newsletter::attributes.status'))
                        ->nullable(),
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('email')
                                ->label(__('vendra-newsletter::attributes.email')),
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
            ->defaultSort(column: 'id', direction: 'desc');
    }
}
