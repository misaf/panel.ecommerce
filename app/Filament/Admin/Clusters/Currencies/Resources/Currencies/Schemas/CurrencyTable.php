<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Schemas;

use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\NameTextColumn;
use App\Tables\Columns\StatusToggleColumn;
use App\Tables\Columns\UpdatedAtTextColumn;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Misaf\Currency\Models\Currency;

final class CurrencyTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                SpatieMediaLibraryImageColumn::make('image')
                    ->circular()
                    ->conversion('thumb-table')
                    ->extraImgAttributes(['class' => 'saturate-50', 'loading' => 'lazy'])
                    ->label(__('form.image'))
                    ->stacked()
                    ->defaultImageUrl(url('coin-payment/images/default.png')),

                NameTextColumn::make('name'),

                TextColumn::make('iso_code')
                    ->badge()
                    ->label(__('form.iso_code'))
                    ->searchable(),

                TextColumn::make('buy_price')
                    ->label(__('form.buy_price'))
                    ->numeric()
                    ->action(
                        Action::make('updateBuyPrice')
                            ->schema([
                                TextInput::make('buy_price')
                                    ->extraInputAttributes(['dir' => 'ltr'])
                                    ->label(__('form.buy_price'))
                                    ->required()
                                    ->numeric(),
                            ])
                            ->action(function (array $data, Currency $record): void {
                                $record->buy_price = $data['buy_price'];
                                $record->save();
                            })
                            ->label(__('بروزرسانی %s', [__('form.buy_price')])),
                    ),

                TextColumn::make('sell_price')
                    ->label(__('form.sell_price'))
                    ->numeric()
                    ->action(
                        Action::make('updateSellPrice')
                            ->schema([
                                TextInput::make('sell_price')
                                    ->extraInputAttributes(['dir' => 'ltr'])
                                    ->label(__('form.sell_price'))
                                    ->required()
                                    ->numeric(),
                            ])
                            ->action(function (array $data, Currency $record): void {
                                $record->sell_price = $data['sell_price'];
                                $record->save();
                            })
                            ->label(__('بروزرسانی %s', [__('form.sell_price')])),
                    ),

                ToggleColumn::make('is_default')
                    ->afterStateUpdated(function (Currency $record, ?string $state): void {
                        Currency::when($state, fn(Builder $query) => $query->whereKeyNot($record->id)->where('is_default', 1))->update(['is_default' => 0]);
                    })
                    ->label(__('form.is_default'))
                    ->onIcon('heroicon-m-bolt'),

                StatusToggleColumn::make('status'),

                CreatedAtTextColumn::make('created_at'),

                UpdatedAtTextColumn::make('updated_at'),
            ])
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('name')
                                ->label(__('currency::attributes.name')),

                            TextConstraint::make('iso_code')
                                ->label(__('currency::attributes.iso_code')),

                            TextConstraint::make('conversion_rate')
                                ->label(__('currency::attributes.conversion_rate')),

                            TextConstraint::make('decimal_place')
                                ->label(__('currency::attributes.conversion_rate')),

                            BooleanConstraint::make('is_default')
                                ->label(__('currency::attributes.is_default')),

                            BooleanConstraint::make('status')
                                ->label(__('currency::attributes.status')),

                            DateConstraint::make('created_at')
                                ->label(__('currency::attributes.created_at')),

                            DateConstraint::make('updated_at')
                                ->label(__('currency::attributes.updated_at')),
                        ]),
                ],
                layout: FiltersLayout::AboveContentCollapsible
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
            ]);
    }
}
