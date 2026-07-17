<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Livewire\Component as Livewire;
use Misaf\VendraCustomPage\Models\CustomPage;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraSupport\Filament\Concerns\HasDefaultAvatarImageUrl;
use Misaf\VendraSupport\Filament\Concerns\InteractsWithTranslatedTableRecords;

final class CustomPageTable
{
    use HasDefaultAvatarImageUrl;
    use InteractsWithTranslatedTableRecords;

    public static function configure(Table $table): Table
    {
        /**
         * @var array<int, Column|ColumnGroup|LayoutComponent> $columns
         */
        $columns = [
            TextColumn::make('row')
                ->label('#')
                ->rowIndex()->sortable(),

            SpatieMediaLibraryImageColumn::make('image')
                ->alignCenter()
                ->collection(CustomPage::MEDIA_COLLECTION)
                ->conversion('thumb-table')
                ->defaultImageUrl(function (CustomPage $record, Livewire $livewire): string {
                    return static::defaultAvatarImageUrl(static::translatedAttribute($record, 'name', $livewire));
                })
                ->extraImgAttributes(['class' => 'saturate-50', 'loading' => 'lazy'])
                ->label(__('vendra-custom-page::attributes.image'))
                ->stacked(),

            TextColumn::make('name')
                ->alignStart()
                ->label(__('vendra-custom-page::attributes.name')),

            TextColumn::make('slug')
                ->alignStart()
                ->label(__('vendra-custom-page::attributes.slug'))
                ->toggleable(isToggledHiddenByDefault: true),

            ToggleColumn::make('status')
                ->label(__('vendra-custom-page::attributes.status'))
                ->onIcon(Heroicon::Bolt),

            TextColumn::make('created_at')
                ->alignCenter()
                ->badge()
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-custom-page::attributes.created_at'))
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
                ->label(__('vendra-custom-page::attributes.updated_at'))
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
                            RelationshipConstraint::make('customPageCategory')
                                ->label(__('vendra-custom-page::navigation.custom_page_category'))
                                ->selectable(
                                    IsRelatedToOperator::make()
                                        ->getOptionLabelFromRecordUsing(function (CustomPageCategory $record, Livewire $livewire) {
                                            return static::translatedAttribute($record, 'name', $livewire);
                                        })
                                        ->preload()
                                        ->searchable()
                                        ->titleAttribute('name'),
                                ),

                            BooleanConstraint::make('status')
                                ->label(__('vendra-custom-page::attributes.status')),

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
                Group::make('customPageCategory.name')
                    ->label(__('vendra-custom-page::navigation.custom_page_category'))
                    ->getTitleFromRecordUsing(function (CustomPage $record, Livewire $livewire) {
                        return $record->customPageCategory
                            ? static::translatedAttribute($record->customPageCategory, 'name', $livewire)
                            : '';
                    })
            );
    }
}
