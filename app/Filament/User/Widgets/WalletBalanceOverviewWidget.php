<?php

declare(strict_types=1);

namespace App\Filament\User\Widgets;

use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Number;
use Misaf\VendraTransaction\Models\Wallet;

final class WalletBalanceOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    protected ?string $pollingInterval = null;

    public static function isDiscovered(): bool
    {
        return true;
    }

    public static function canView(): bool
    {
        return self::wallets()->isNotEmpty();
    }

    /**
     * @return array<int, Stat>
     */
    protected function getStats(): array
    {
        return self::wallets()
            ->map(fn(Wallet $wallet): Stat => Stat::make("wallet_{$wallet->id}_balance_stats", Number::format($wallet->balance))
                ->color('primary')
                ->description(__('vendra-transaction::widgets.wallet_balance_stats_description'))
                ->descriptionIcon(Heroicon::Banknotes, IconPosition::Before)
                ->icon(Heroicon::OutlinedWallet)
                ->label($wallet->currency->iso_code))
            ->all();
    }

    /**
     * @return Collection<int, Wallet>
     */
    private static function wallets(): Collection
    {
        return Wallet::query()
            ->where('user_id', filament()->auth()->user()?->getAuthIdentifier())
            ->with('currency')
            ->orderBy('id')
            ->get();
    }
}
