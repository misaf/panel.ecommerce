<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Tables;

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
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Misaf\VendraCurrency\Models\CurrencyCategory;
use Misaf\VendraSupport\Filament\Concerns\HasDefaultAvatarImageUrl;

final class CurrencyCategoryTable
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
                ->rowIndex()
                ->sortable(['id']),

            SpatieMediaLibraryImageColumn::make('image')
                ->alignCenter()
                ->collection(CurrencyCategory::MEDIA_COLLECTION)
                ->conversion('thumb-table')
                ->defaultImageUrl(fn(CurrencyCategory $record): string => static::defaultAvatarImageUrl($record->name))
                ->extraImgAttributes(['class' => 'saturate-50', 'loading' => 'lazy'])
                ->label(__('vendra-currency::attributes.image'))
                ->stacked(),

            BadgeableColumn::make('name')
                ->alignStart()
                ->label(__('vendra-currency::attributes.name'))
                ->searchable()
                ->suffixBadges([
                    Badge::make('count')
                        ->label(fn(CurrencyCategory $record): string => (string) Number::format(static::currencyCount($record)))
                        ->size(Size::Small),
                ])
                ->suffix(''),

            TextColumn::make('description')
                ->label(__('vendra-currency::attributes.description'))
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('slug')
                ->alignStart()
                ->label(__('vendra-currency::attributes.slug'))
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),

            ToggleColumn::make('status')
                ->label(__('vendra-currency::attributes.status'))
                ->onIcon(Heroicon::Bolt),

            TextColumn::make('created_at')
                ->alignCenter()
                ->badge()
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-currency::attributes.created_at'))
                ->sinceTooltip()
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
                ->when(
                    app()->isLocale('fa'),
                    fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                    fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')
                ),
        ];

        return $table
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->withCount('currencies'))
            ->description(__('vendra-currency::tables.description.currency_categories'))
            ->emptyStateHeading(__('vendra-currency::tables.empty_state.heading.currency_categories'))
            ->emptyStateDescription(__('vendra-currency::tables.empty_state.description.currency_categories'))
            ->emptyStateIcon(Heroicon::OutlinedCircleStack)
            ->columns($columns)
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('name')
                                ->label(__('vendra-currency::attributes.name')),

                            TextConstraint::make('slug')
                                ->label(__('vendra-currency::attributes.slug')),

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
            ->reorderable(column: 'position', direction: 'desc');
    }

    private static function currencyCount(CurrencyCategory $record): int
    {
        $count = $record->getAttribute('currencies_count');

        return is_numeric($count) ? (int) $count : 0;
    }
}
