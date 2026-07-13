<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Tables;

use Awcodes\BadgeableColumn\Components\Badge;
use Awcodes\BadgeableColumn\Components\BadgeableColumn;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Size;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\Layout\Component as LayoutComponent;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Actions\SetBuyPriceAction;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Actions\SetDefaultCurrencyAction;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Actions\SetSellPriceAction;
use Misaf\VendraCurrency\Models\Currency;
use Misaf\VendraCurrency\Models\CurrencyCategory;
use Misaf\VendraSupport\Filament\Concerns\HasDefaultAvatarImageUrl;

final class CurrencyTable
{
    use HasDefaultAvatarImageUrl;

    public static function configure(Table $table): Table
    {
        /**
         * @var array<int, Column|ColumnGroup|LayoutComponent> $columns
         */
        $columns = [
            TextColumn::make('row')
                ->label('#')
                ->rowIndex(),

            SpatieMediaLibraryImageColumn::make('image')
                ->alignCenter()
                ->collection('currencies')
                ->conversion('thumb-table')
                ->defaultImageUrl(fn(Currency $record): string =>  static::defaultAvatarImageUrl($record->name))
                ->extraImgAttributes(['class' => 'saturate-50', 'loading' => 'lazy'])
                ->label(__('vendra-product::attributes.image'))
                ->stacked(),

            BadgeableColumn::make('name')
                ->alignStart()
                ->description(fn(Currency $record): string => $record->description)
                ->label(__('vendra-currency::attributes.name'))
                ->searchable()
                ->suffixBadges([
                    Badge::make('status')
                        ->label(fn(Currency $record) => $record->iso_code)
                        ->size(Size::Small),
                ]),

            TextColumn::make('slug')
                ->alignStart()
                ->label(__('vendra-currency::attributes.slug'))
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('iso_code')
                ->alignStart()
                ->label(__('vendra-currency::attributes.iso_code'))
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('conversion_rate')
                ->alignStart()
                ->label(__('vendra-currency::attributes.conversion_rate'))
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('decimal_place')
                ->alignStart()
                ->label(__('vendra-currency::attributes.decimal_place'))
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('buy_price')
                ->label(__('vendra-currency::attributes.buy_price'))
                ->numeric()
                ->action(
                    Action::make('updateBuyPrice')
                        ->schema([
                            TextInput::make('buy_price')
                                ->default(fn(Currency $record) => $record->buy_price)
                                ->extraInputAttributes(['dir' => 'ltr'])
                                ->label(__('vendra-currency::attributes.buy_price'))
                                ->inputMode('number')
                                ->numeric()
                                ->required(),
                        ])
                        ->action(function (Currency $record, array $data): void {
                            $price = $data['buy_price'] ?? null;

                            if ( ! is_numeric($price)) {
                                return;
                            }

                            $buyPrice = (new SetBuyPriceAction())->execute($record, (int) $price);

                            if ($buyPrice) {
                                Notification::make()
                                    ->title(__('vendra-currency::messages.buy_price_changed_successfully', ['iso_code' => $record->iso_code]))
                                    ->success()
                                    ->send();
                            }
                        })
                        ->label(fn(Currency $record) => __('vendra-currency::messages.update_name', ['name' => $record->name]))
                        ->requiresConfirmation(),
                ),

            TextColumn::make('sell_price')
                ->label(__('vendra-currency::attributes.sell_price'))
                ->numeric()
                ->action(
                    Action::make('updateSellPrice')
                        ->schema([
                            TextInput::make('sell_price')
                                ->default(fn(Currency $record) => $record->sell_price)
                                ->extraInputAttributes(['dir' => 'ltr'])
                                ->inputMode('number')
                                ->label(__('vendra-currency::attributes.sell_price'))
                                ->numeric()
                                ->required(),
                        ])
                        ->action(function (array $data, Currency $record): void {
                            $price = $data['sell_price'] ?? null;

                            if ( ! is_numeric($price)) {
                                return;
                            }

                            $sellPrice = (new SetSellPriceAction())->execute($record, (int) $price);

                            if ($sellPrice) {
                                Notification::make()
                                    ->title(__('vendra-currency::messages.sell_price_changed_successfully', ['iso_code' => $record->iso_code]))
                                    ->success()
                                    ->send();
                            }
                        })
                        ->label(fn(Currency $record) => __('vendra-currency::messages.update_name', ['name' => $record->name]))
                        ->requiresConfirmation(),
                ),

            ToggleColumn::make('is_default')
                ->afterStateUpdated(function (Currency $record, ?string $state): void {
                    if ($state) {
                        (new SetDefaultCurrencyAction())->execute($record);
                    }
                })
                ->label(__('vendra-currency::attributes.is_default'))
                ->onIcon('heroicon-m-bolt'),

            ToggleColumn::make('status')
                ->label(__('vendra-currency::attributes.status'))
                ->onIcon('heroicon-m-bolt'),

            TextColumn::make('created_at')
                ->alignCenter()
                ->badge()
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-currency::attributes.created_at'))
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
                ->label(__('vendra-currency::attributes.updated_at'))
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
                            RelationshipConstraint::make('currencyCategory')
                                ->label(__('vendra-currency::navigation.currency_category'))
                                ->selectable(
                                    IsRelatedToOperator::make()
                                        ->getOptionLabelFromRecordUsing(function (CurrencyCategory $record) {
                                            return $record->getAttributeValue('name');
                                        })
                                        ->preload()
                                        ->searchable()
                                        ->titleAttribute('name'),
                                ),

                            TextConstraint::make('name')
                                ->label(__('vendra-currency::attributes.name')),

                            TextConstraint::make('slug')
                                ->label(__('vendra-currency::attributes.slug')),

                            TextConstraint::make('iso_code')
                                ->label(__('vendra-currency::attributes.iso_code')),

                            BooleanConstraint::make('is_default')
                                ->label(__('vendra-currency::attributes.is_default')),

                            BooleanConstraint::make('status')
                                ->label(__('vendra-currency::attributes.status')),
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
            ->defaultSort(column: 'position', direction: 'desc')
            ->reorderable(column: 'position', direction: 'desc')
            ->defaultGroup(
                Group::make('currencyCategory.name')
                    ->label(__('vendra-currency::navigation.currency_category'))
            );
    }
}
