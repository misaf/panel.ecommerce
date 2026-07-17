<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\Tables;

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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Livewire\Component as Livewire;
use Misaf\VendraBlog\Models\BlogPostCategory;
use Misaf\VendraSupport\Filament\Concerns\HasDefaultAvatarImageUrl;
use Misaf\VendraSupport\Filament\Concerns\InteractsWithTranslatedTableRecords;

final class BlogPostCategoryTable
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
                ->collection(BlogPostCategory::MEDIA_COLLECTION)
                ->conversion('thumb-table')
                ->defaultImageUrl(function (BlogPostCategory $record, Livewire $livewire): string {
                    return static::defaultAvatarImageUrl(static::translatedAttribute($record, 'name', $livewire));
                })
                ->extraImgAttributes(['class' => 'saturate-50', 'loading' => 'lazy'])
                ->label(__('vendra-blog::attributes.image'))
                ->stacked(),

            BadgeableColumn::make('name')
                ->alignStart()
                ->description(function (Livewire $livewire, BlogPostCategory $record): string {
                    return static::translatedAttribute($record, 'description', $livewire);
                })
                ->icon(Heroicon::FolderPlus)
                ->label(__('vendra-blog::attributes.name'))
                ->suffixBadges([
                    Badge::make('count')
                        ->label(fn(BlogPostCategory $record): string => (string) Number::format(static::integerAttribute($record, 'blog_posts_count')))
                        ->size(Size::Small),
                ]),

            TextColumn::make('slug')
                ->alignStart()
                ->label(__('vendra-blog::attributes.slug'))
                ->toggleable(isToggledHiddenByDefault: true),

            ToggleColumn::make('status')
                ->label(__('vendra-blog::attributes.status'))
                ->onIcon(Heroicon::Bolt),

            TextColumn::make('created_at')
                ->alignCenter()
                ->badge()
                ->extraCellAttributes(['dir' => 'ltr'])
                ->label(__('vendra-blog::attributes.created_at'))
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
                ->label(__('vendra-blog::attributes.updated_at'))
                ->sinceTooltip()
                ->toggleable(isToggledHiddenByDefault: true)
                ->when(
                    app()->isLocale('fa'),
                    fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', latinNumbers: true),
                    fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')
                ),
        ];

        return $table
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->withCount('blogPosts'))
            ->columns($columns)
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            BooleanConstraint::make('status')
                                ->label(__('vendra-blog::attributes.status')),
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

}
