<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\Tables;

use Awcodes\BadgeableColumn\Components\Badge;
use Awcodes\BadgeableColumn\Components\BadgeableColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Livewire\Component as Livewire;
use Misaf\VendraFaq\Models\FaqCategory;
use Misaf\VendraSupport\Filament\Concerns\HasDefaultAvatarImageUrl;

final class FaqCategoryTable
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
                ->collection('faqs/categories')
                ->conversion('thumb-table')
                ->defaultImageUrl(function (FaqCategory $record, Livewire $livewire): string {
                    return static::defaultAvatarImageUrl($record->getTranslation('name', $livewire->activeLocale));
                })
                ->extraImgAttributes(['class' => 'saturate-50', 'loading' => 'lazy'])
                ->label(__('vendra-faq::attributes.image'))
                ->stacked(),

            BadgeableColumn::make('name')
                ->alignStart()
                ->description(function (Livewire $livewire, FaqCategory $record): string {
                    return $record->getTranslation('description', $livewire->activeLocale);
                })
                ->icon('heroicon-m-folder-plus')
                ->label(__('vendra-faq::attributes.name'))
                ->suffixBadges([
                    Badge::make('count')
                        ->label(fn(FaqCategory $record): string => (string) Number::format($record->faqs()->count()))
                        ->size(Size::Small),
                ]),

            TextColumn::make('slug')
                ->alignStart()
                ->label(__('vendra-faq::attributes.slug'))
                ->toggleable(isToggledHiddenByDefault: true),

            ToggleColumn::make('status')
                ->label(__('vendra-faq::attributes.status'))
                ->onIcon('heroicon-m-bolt'),

            TextColumn::make('created_at')
                ->alignCenter()
                ->badge()
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-faq::attributes.created_at'))
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
                ->label(__('vendra-faq::attributes.updated_at'))
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
                            BooleanConstraint::make('status')
                                ->label(__('vendra-faq::attributes.status')),
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
            ->reorderable(column: 'position', direction: 'desc');
    }
}
