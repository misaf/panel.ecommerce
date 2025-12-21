<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSendHistorySubscribers\Schemas;

use Awcodes\BadgeableColumn\Components\Badge;
use Awcodes\BadgeableColumn\Components\BadgeableColumn;
use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Misaf\Newsletter\Enums\NewsletterSendHistoryStatusEnum;
use Misaf\Newsletter\Models\NewsletterSendHistorySubscriber;

final class NewsletterSendHistorySubscriberTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->with('newsletterSubscriber.user'))
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                BadgeableColumn::make('newsletterSubscriber.email')
                    ->alignEnd()
                    ->label(__('newsletter/attributes.email'))
                    ->searchable()
                    ->suffixBadges([
                        Badge::make('username')
                            ->label(fn(NewsletterSendHistorySubscriber $record) => $record->newsletterSubscriber->user?->username)
                            ->visible(fn(NewsletterSendHistorySubscriber $record) => $record->newsletterSubscriber->user?->username)
                            ->color('primary')
                            ->size(Size::Small),

                        Badge::make('status')
                            ->label(fn(NewsletterSendHistorySubscriber $record) => $record->status->getLabel())
                            ->color(function ($record) {
                                return match ($record->status) {
                                    NewsletterSendHistoryStatusEnum::FAILED => 'danger',
                                    default                                 => 'success'
                                };
                            })
                            ->size(Size::Small),

                        Badge::make('failed')
                            ->label(__('newsletter/attributes.failed_message'))
                            ->color('danger')
                            ->visible(fn(NewsletterSendHistorySubscriber $record) => null !== $record->failed_message)
                            ->size(Size::Small),
                    ])
                    ->actions([
                        Action::make('failed_message')
                            ->defaultColor('danger')
                            ->modal()
                            ->modalAlignment(Alignment::Center)
                            ->modalCancelAction(false)
                            ->modalDescription(fn($record) => $record->failed_message)
                            ->modalHeading(__('newsletter/attributes.failed_message'))
                            ->modalIcon('heroicon-o-exclamation-triangle')
                            ->modalSubmitAction(false)
                            ->modalWidth(Width::Medium)
                            ->visible(fn($record) => $record->failed_message)
                    ]),

                TextColumn::make('sent_at')
                    ->alignCenter()
                    ->badge()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('newsletter/attributes.sent_at'))
                    ->sinceTooltip()
                    ->sortable()
                    ->unless(app()->isLocale('fa'), fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i:s', toLatin: true), fn(TextColumn $column) => $column->dateTime('Y-m-d H:i:s')),

                TextColumn::make('failed_at')
                    ->alignCenter()
                    ->badge()
                    ->color('danger')
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('newsletter/attributes.failed_at'))
                    ->sinceTooltip()
                    ->sortable()
                    ->unless(app()->isLocale('fa'), fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i:s', toLatin: true), fn(TextColumn $column) => $column->dateTime('Y-m-d H:i:s'))

                // TextColumn::make('status')
                //     ->alignCenter()
                //     ->label(__('newsletter/attributes.status')),
            ])
            ->headerActions([
                CreateAction::make(),

                AttachAction::make(),
            ]);
    }
}
