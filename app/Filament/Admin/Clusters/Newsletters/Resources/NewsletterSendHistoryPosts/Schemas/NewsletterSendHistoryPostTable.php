<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSendHistoryPosts\Schemas;

use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\SlugTextColumn;
use App\Tables\Columns\UpdatedAtTextColumn;
use Awcodes\BadgeableColumn\Components\Badge;
use Awcodes\BadgeableColumn\Components\BadgeableColumn;
use Filament\Support\Enums\Size;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use Misaf\Newsletter\Enums\NewsletterPostStatusEnum;
use Misaf\Newsletter\Models\NewsletterPost;

final class NewsletterSendHistoryPostTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->with('newsletter'))
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                BadgeableColumn::make('name')
                    ->alignStart()
                    ->label(__('newsletter/attributes.name'))
                    ->searchable()
                    ->suffixBadges([
                        Badge::make('status')
                            ->label(fn(NewsletterPost $record) => $record->status->getLabel())
                            ->color('success')
                            ->size(Size::Small),
                    ]),

                SlugTextColumn::make('slug')
                    ->alignLeft()
                    ->label(__('newsletter/attributes.slug'))
                    ->toggleable(isToggledHiddenByDefault: true),

                CreatedAtTextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->label(__('newsletter/attributes.created_at')),

                UpdatedAtTextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i')
                    ->label(__('newsletter/attributes.updated_at')),
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('name')
                            ->label(__('newsletter/attributes.name')),

                        TextConstraint::make('slug')
                            ->label(__('newsletter/attributes.slug')),

                        SelectConstraint::make('status')
                            ->label(__('newsletter/attributes.status'))
                            ->multiple()
                            ->options(NewsletterPostStatusEnum::class),

                        DateConstraint::make('sent_at')
                            ->label(__('newsletter/attributes.sent_at')),

                        DateConstraint::make('created_at')
                            ->label(__('newsletter/attributes.created_at')),

                        DateConstraint::make('updated_at')
                            ->label(__('newsletter/attributes.updated_at')),
                    ]),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->headerActions([
                LocaleSwitcher::make(),
            ]);
    }
}
