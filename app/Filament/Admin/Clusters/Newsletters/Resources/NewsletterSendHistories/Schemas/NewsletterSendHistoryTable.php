<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSendHistories\Schemas;

use App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSendHistories\NewsletterSendHistoryResource;
use Awcodes\BadgeableColumn\Components\Badge;
use Awcodes\BadgeableColumn\Components\BadgeableColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Misaf\Newsletter\Enums\NewsletterSendHistoryStatusEnum;
use Misaf\Newsletter\Models\NewsletterSendHistory;

final class NewsletterSendHistoryTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                BadgeableColumn::make('token')
                    ->alignCenter()
                    ->copyable()
                    ->copyMessage(__('newsletter::messages.token_copied'))
                    ->copyMessageDuration(1500)
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('newsletter::attributes.token'))
                    ->searchable()
                    ->prefixBadges([
                        Badge::make('hot1')
                            ->label(fn(NewsletterSendHistory $record) => $record->status->getLabel())
                            ->color(function (NewsletterSendHistory $record) {
                                return match ($record->status) {
                                    NewsletterSendHistoryStatusEnum::SENDING => 'warning',
                                    NewsletterSendHistoryStatusEnum::SENT    => 'success',
                                    default                                  => 'danger',
                                };
                            })
                            ->size(Size::Small),
                    ]),

                BadgeableColumn::make('total_subscribers')
                    ->alignCenter()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(function () {
                        $totalSubscribers = __('newsletter::attributes.total_subscribers');
                        $sentCount = __('newsletter::attributes.sent_count');
                        $failedCount = __('newsletter::attributes.failed_count');
                        return $totalSubscribers . ' : ' . $sentCount . ' / ' . $failedCount;
                    })
                    ->prefixBadges([
                        Badge::make('failed_count')
                            ->label(fn(NewsletterSendHistory $record) => Number::format($record->failed_count))
                            ->color('danger')
                            ->size(Size::Small),

                        Badge::make('sent_count')
                            ->label(fn(NewsletterSendHistory $record) => Number::format($record->sent_count))
                            ->color('success')
                            ->size(Size::Small),
                    ])
                    ->separator(':'),

                TextColumn::make('started_at')
                    ->alignCenter()
                    ->badge()
                    ->dateTime('Y-m-d H:i')
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('newsletter::attributes.started_at'))
                    ->sinceTooltip()
                    ->sortable()
                    ->unless(app()->isLocale('fa'), fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', toLatin: true), fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')),

                TextColumn::make('completed_at')
                    ->alignCenter()
                    ->badge()
                    ->dateTime('Y-m-d H:i')
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('newsletter::attributes.completed_at'))
                    ->sinceTooltip()
                    ->sortable()
                    ->unless(app()->isLocale('fa'), fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d H:i', toLatin: true), fn(TextColumn $column) => $column->dateTime('Y-m-d H:i')),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->url(fn(NewsletterSendHistory $record): string => NewsletterSendHistoryResource::getUrl('view', ['record' => $record])),
                ]),
            ]);
    }
}
