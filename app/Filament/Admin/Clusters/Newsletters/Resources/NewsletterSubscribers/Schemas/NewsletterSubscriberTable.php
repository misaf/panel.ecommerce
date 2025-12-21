<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSubscribers\Schemas;

use App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSubscribers\Actions\SendBulkEmailAction;
use App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSubscribers\Actions\SendCustomBulkEmailAction;
use App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSubscribers\Imports\NewsletterSubscriberImporter;
use App\Filament\Admin\Resources\Tags\Actions\AddTagAction;
use Awcodes\BadgeableColumn\Components\Badge;
use Awcodes\BadgeableColumn\Components\BadgeableColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ImportAction;
use Filament\Support\Enums\Size;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Misaf\Newsletter\Models\NewsletterSubscriber;
use Misaf\Tag\Models\Tag;

final class NewsletterSubscriberTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->with('user'))
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                BadgeableColumn::make('email')
                    ->alignLeft()
                    ->copyable()
                    ->copyMessage(__('form.saved'))
                    ->copyMessageDuration(1500)
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('newsletter/attributes.email'))
                    ->searchable()
                    ->prefixBadges([
                        Badge::make('username')
                            ->color('primary')
                            ->label(fn(NewsletterSubscriber $record) => $record->user?->username)
                            ->size(Size::Small)
                            ->visible(fn(NewsletterSubscriber $record) => $record->user?->username),
                    ]),

                SpatieTagsColumn::make('tags')
                    // ->action(AddTagAction::make())
                    ->label(__('tag::navigation.tag'))
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->alignCenter()
                    ->badge()
                    ->dateTime('Y-m-d H:i')
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('newsletter/attributes.created_at'))
                    ->sinceTooltip()
                    ->sortable()
                    ->toggleable()
                    ->unless(app()->isLocale('fa'), fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', toLatin: true), fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')),

                TextColumn::make('updated_at')
                    ->alignCenter()
                    ->badge()
                    ->dateTime('Y-m-d H:i')
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('newsletter/attributes.updated_at'))
                    ->sinceTooltip()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->unless(app()->isLocale('fa'), fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d', toLatin: true)),
            ])
            ->headerActions([
                ImportAction::make('import')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->importer(NewsletterSubscriberImporter::class)
                    ->size(Size::Small),

                SendCustomBulkEmailAction::make(),
            ])
            ->filters([
                TernaryFilter::make('user_id')
                    ->label(__('user::attributes.username'))
                    ->nullable()
                    ->queries(
                        true: fn(Builder $query) => $query->whereHas('user'),
                        false: fn(Builder $query) => $query->whereDoesntHave('user'),
                        blank: fn(Builder $query) => $query,
                    ),

                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('email')
                            ->label(__('newsletter/attributes.email')),

                        DateConstraint::make('created_at')
                            ->label(__('newsletter/attributes.created_at')),

                        DateConstraint::make('updated_at')
                            ->label(__('newsletter/attributes.updated_at')),

                        RelationshipConstraint::make('tags')
                            ->label(__('tag::navigation.tag'))
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->getOptionLabelFromRecordUsing(fn(Tag $record) => $record->getAttributeValue('name'))
                                    ->preload()
                                    ->searchable()
                                    ->titleAttribute('name'),
                            ),
                    ]),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                ActionGroup::make([
                    AddTagAction::make(),

                    EditAction::make(),

                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    SendBulkEmailAction::make(),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
