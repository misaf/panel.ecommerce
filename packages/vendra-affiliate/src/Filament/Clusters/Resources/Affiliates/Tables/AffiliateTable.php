<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Config;
use Misaf\VendraAffiliate\Actions\ProcessAffiliatePayout;
use Misaf\VendraAffiliate\Enums\AffiliatePayoutPolicyEnum;
use Misaf\VendraAffiliate\Enums\AffiliateStatusEnum;
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraSupport\Support\TagIntegration;

final class AffiliateTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('user.username')
                    ->label(__('vendra-affiliate::attributes.user'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->copyable()
                    ->copyMessage(__('vendra-affiliate::messages.link_copied'))
                    ->copyMessageDuration(1500)
                    ->copyableState(fn(Affiliate $record): string => $record->referralUrl())
                    ->label(__('vendra-affiliate::attributes.code'))
                    ->searchable()
                    ->tooltip(fn(Affiliate $record): string => $record->referralUrl()),

                ...self::tagColumns(),

                TextColumn::make('commission_percent')
                    ->alignCenter()
                    ->label(__('vendra-affiliate::attributes.commission_percent'))
                    ->sortable()
                    ->suffix('%'),

                TextColumn::make('pending_balance')
                    ->alignCenter()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-affiliate::attributes.pending_balance'))
                    ->numeric(locale: 'en', maxDecimalPlaces: 0)
                    ->state(fn(Affiliate $record): int => $record->pendingBalance()),

                TextColumn::make('status')
                    ->alignCenter()
                    ->badge()
                    ->label(__('vendra-affiliate::attributes.status')),

                TextColumn::make('created_at')
                    ->alignCenter()
                    ->badge()
                    ->dateTime('Y-m-d H:i')
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-affiliate::attributes.created_at'))
                    ->sinceTooltip()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->alignCenter()
                    ->badge()
                    ->dateTime('Y-m-d H:i')
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-affiliate::attributes.updated_at'))
                    ->sinceTooltip()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(
                [
                    SelectFilter::make('status')
                        ->label(__('vendra-affiliate::attributes.status'))
                        ->options(AffiliateStatusEnum::class),

                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('code')
                                ->label(__('vendra-affiliate::attributes.code')),

                            NumberConstraint::make('commission_percent')
                                ->label(__('vendra-affiliate::attributes.commission_percent')),

                            DateConstraint::make('created_at')
                                ->label(__('vendra-affiliate::attributes.created_at')),
                        ]),
                ],
                layout: FiltersLayout::AboveContentCollapsible,
            )
            ->recordActions([
                ActionGroup::make([
                    self::processPayoutAction(),

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
            ->defaultSort(column: 'created_at', direction: 'desc');
    }

    public static function processPayoutAction(): Action
    {
        return Action::make('processPayout')
            ->authorize(fn(): bool => (bool) auth()->user()?->can(AffiliatePayoutPolicyEnum::Process->value))
            ->color('success')
            ->disabled(fn(Affiliate $record): bool => $record->pendingBalance() < Config::integer('vendra-affiliate.payout.minimum', 0))
            ->icon('heroicon-o-banknotes')
            ->label(__('vendra-affiliate::messages.process_payout'))
            ->requiresConfirmation()
            ->modalDescription(fn(Affiliate $record): string => __('vendra-affiliate::messages.process_payout_description', [
                'amount' => $record->pendingBalance(),
            ]))
            ->action(function (Affiliate $record): void {
                app(ProcessAffiliatePayout::class)->onQueue()->execute($record);

                Notification::make()
                    ->success()
                    ->title(__('vendra-affiliate::messages.process_payout_queued'))
                    ->send();
            });
    }

    /** @return list<TextColumn> */
    private static function tagColumns(): array
    {
        if ( ! TagIntegration::isAvailable()) {
            return [];
        }

        return [
            TextColumn::make('tags.name')
                ->badge()
                ->label(__('vendra-affiliate::attributes.tags'))
                ->toggleable(),
        ];
    }
}
