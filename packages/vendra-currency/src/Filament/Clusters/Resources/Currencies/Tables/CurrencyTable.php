<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Tables;

use Awcodes\BadgeableColumn\Components\Badge;
use Awcodes\BadgeableColumn\Components\BadgeableColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\Layout\Component as LayoutComponent;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Misaf\VendraCurrency\Actions\SetDefaultCurrencyAction;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Actions\UpdateBuyPriceAction;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Actions\UpdateSellPriceAction;
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
                ->rowIndex()->sortable(['id']),

            SpatieMediaLibraryImageColumn::make('image')
                ->alignCenter()
                ->collection(Currency::MEDIA_COLLECTION)
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
                ->action(UpdateBuyPriceAction::make()),

            TextColumn::make('sell_price')
                ->label(__('vendra-currency::attributes.sell_price'))
                ->numeric()
                ->action(UpdateSellPriceAction::make()),

            ToggleColumn::make('is_default')
                ->afterStateUpdated(function (Currency $record, ?string $state): void {
                    if ($state) {
                        (new SetDefaultCurrencyAction())->execute($record);
                    }
                })
                ->label(__('vendra-currency::attributes.is_default'))
                ->onIcon(Heroicon::Bolt),

            ToggleColumn::make('status')
                ->label(__('vendra-currency::attributes.status'))
                ->onIcon(Heroicon::Bolt),

            TextColumn::make('created_at')
                ->alignCenter()
                ->badge()
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-currency::attributes.created_at'))
                ->sinceTooltip()
                ->toggleable(isToggledHiddenByDefault: true)
                ->when(
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
                ->when(
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

                            NumberConstraint::make('position'),
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
            ->defaultSort(column: 'id', direction: 'desc')
            ->reorderable(column: 'position', direction: 'desc')
            ->defaultGroup(
                Group::make('currencyCategory.name')
                    ->label(__('vendra-currency::navigation.currency_category'))
            );
    }
}
