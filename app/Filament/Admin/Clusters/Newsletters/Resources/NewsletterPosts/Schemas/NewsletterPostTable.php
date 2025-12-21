<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterPosts\Schemas;

use Awcodes\BadgeableColumn\Components\Badge;
use Awcodes\BadgeableColumn\Components\BadgeableColumn;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
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
use Misaf\Newsletter\Actions\Newsletter\SendWithPostAction;
use Misaf\Newsletter\Enums\NewsletterPostStatusEnum;
use Misaf\Newsletter\Models\NewsletterPost;

final class NewsletterPostTable
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
                    ->label(__('newsletter::attributes.name'))
                    ->searchable()
                    ->suffixBadges([
                        Badge::make('status')
                            ->label(fn(NewsletterPost $record) => $record->status->getLabel())
                            ->color(function (NewsletterPost $record) {
                                return match ($record->status) {
                                    NewsletterPostStatusEnum::DRAFT => 'warning',
                                    default                         => 'success',
                                };
                            })
                            ->size(Size::Small),
                    ]),

                TextColumn::make('slug')
                    ->alignLeft()
                    ->label(__('newsletter::attributes.slug'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->alignCenter()
                    ->badge()
                    ->dateTime('Y-m-d H:i')
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('newsletter::attributes.created_at'))
                    ->sinceTooltip()
                    ->sortable()
                    ->unless(app()->isLocale('fa'), fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', toLatin: true), fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')),

                TextColumn::make('updated_at')
                    ->alignCenter()
                    ->badge()
                    ->dateTime('Y-m-d H:i')
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('newsletter::attributes.updated_at'))
                    ->sinceTooltip()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->unless(app()->isLocale('fa'), fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', toLatin: true), fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')),
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('name')
                            ->label(__('newsletter::attributes.name')),

                        TextConstraint::make('slug')
                            ->label(__('newsletter::attributes.slug')),

                        SelectConstraint::make('status')
                            ->label(__('newsletter::attributes.status'))
                            ->multiple()
                            ->options(NewsletterPostStatusEnum::class),

                        DateConstraint::make('sent_at')
                            ->label(__('newsletter::attributes.sent_at')),

                        DateConstraint::make('created_at')
                            ->label(__('newsletter::attributes.created_at')),

                        DateConstraint::make('updated_at')
                            ->label(__('newsletter::attributes.updated_at')),
                    ]),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),

                    EditAction::make(),

                    DeleteAction::make(),

                    Action::make('send')
                        ->label(__('newsletter::actions.send'))
                        ->icon('heroicon-o-paper-airplane')
                        ->action(function (NewsletterPost $record): void {
                            try {
                                app(SendWithPostAction::class)->execute($record->newsletter, $record);

                                Notification::make()
                                    ->title(__('newsletter::notifications.send.success'))
                                    ->success()
                                    ->send();
                            } catch (Exception $e) {
                                Notification::make()
                                    ->title(__('newsletter::notifications.send.failed.title', ['newsletter' => $record->newsletter->name]))
                                    ->body(__('newsletter::notifications.send.failed.body', ['error' => $e->getMessage()]))
                                    ->danger()
                                    ->send();
                            }
                        }),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                LocaleSwitcher::make(),
            ]);
    }
}
