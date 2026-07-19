<?php

declare(strict_types=1);

namespace App\Filament\User\Widgets;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Size;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Misaf\VendraTransaction\Enums\TransactionTypeEnum;
use Misaf\VendraTransaction\Models\Transaction;
use Misaf\VendraTransaction\States\Review;

final class LatestTransactionTableWidget extends BaseWidget
{
    use WithRateLimiting;

    /**
     * @var int|string|array<string, int|null>
     */
    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 1;
    }

    protected static ?int $sort = 2;

    public static function isDiscovered(): bool
    {
        return true;
    }

    public static function canView(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('vendra-transaction::widgets.recent_transaction_table'))
            ->query(
                Transaction::query()
                    ->whereHas('wallet', fn(Builder $query) => $query->where('user_id', $this->getAuthenticatedUser()?->getAuthIdentifier()))
                    ->where('created_at', '>=', now()->subDays(30))
                    ->with(['transactionGateway', 'wallet.currency']),
            )
            ->columns([
                TextColumn::make('transactionGateway.name')
                    ->label(__('vendra-transaction::navigation.transaction_gateway')),

                TextColumn::make('transaction_type')
                    ->badge()
                    ->label(__('vendra-transaction::attributes.transaction_type')),

                TextColumn::make('token')
                    ->alignCenter()
                    ->copyable()
                    ->copyMessage(__('vendra-transaction::messages.token_copied'))
                    ->copyMessageDuration(1500)
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->formatStateUsing(function (string $state): string {
                        return Str::of($state)->split(4)->implode(' ');
                    })
                    ->label(__('vendra-transaction::attributes.token'))
                    ->searchable(),

                TextColumn::make('amount')
                    ->alignCenter()
                    ->copyable()
                    ->copyMessage(__('vendra-transaction::messages.amount_copied'))
                    ->copyMessageDuration(1500)
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('vendra-transaction::attributes.amount'))
                    ->numeric(locale: 'en', maxDecimalPlaces: 0),

                TextColumn::make('status')
                    ->alignStart()
                    ->badge()
                    ->color(fn(Transaction $record): array => $record->status->getColor())
                    ->formatStateUsing(fn(Transaction $record): string => $record->status->getLabel())
                    ->label(__('vendra-transaction::attributes.status')),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label(__('vendra-transaction::attributes.created_at')),
            ])
            ->recordActions([
                Action::make('decline')
                    ->action(function (Transaction $record): void {
                        try {
                            $this->rateLimit(1);

                            $record->decline();
                        } catch (TooManyRequestsException $exception) {
                            $this->sendRateLimitNotification($exception);
                        }
                    })
                    ->button()
                    ->color('danger')
                    ->icon('heroicon-s-no-symbol')
                    ->label(__('billing.decline'))
                    ->requiresConfirmation()
                    ->size(Size::ExtraSmall)
                    ->visible(function (Transaction $record): bool {
                        return TransactionTypeEnum::Withdrawal === $record->transaction_type
                            && $record->status instanceof Review;
                    }),
            ]);
    }

    private function getAuthenticatedUser(): ?Authenticatable
    {
        return filament()->auth()->user();
    }

    private function sendRateLimitNotification(TooManyRequestsException $exception): void
    {
        $seconds = $exception->secondsUntilAvailable;

        Notification::make()
            ->title(__('billing.rate_limit_title'))
            ->body(__('billing.rate_limit_body', ['seconds' => is_numeric($seconds) ? (int) $seconds : 0]))
            ->danger()
            ->send();
    }
}
