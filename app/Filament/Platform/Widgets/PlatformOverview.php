<?php

declare(strict_types=1);

namespace App\Filament\Platform\Widgets;

use App\Filament\Concerns\BuildsDailyTrend;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraTenant\Models\Tenant;

final class PlatformOverview extends StatsOverviewWidget
{
    use BuildsDailyTrend;

    protected function getStats(): array
    {
        $expiringSoon = Subscription::query()
            ->active()
            ->whereNotNull('ends_at')
            ->whereBetween('ends_at', [now(), now()->addDays(7)])
            ->count();

        return [
            Stat::make(__('platform.accounts'), Account::query()->count())
                ->icon(Heroicon::OutlinedBuildingOffice2)
                ->chart($this->dailyTrend(Account::query())),
            Stat::make(__('platform.websites'), Tenant::query()->count())
                ->icon(Heroicon::OutlinedGlobeAlt)
                ->chart($this->dailyTrend(Tenant::query())),
            Stat::make(__('platform.active_subscriptions'), Subscription::query()->active()->count())
                ->icon(Heroicon::OutlinedCheckBadge)
                ->color('success')
                ->chart($this->dailyTrend(Subscription::query(), 'starts_at')),
            Stat::make(__('platform.expiring_soon'), $expiringSoon)
                ->icon(Heroicon::OutlinedClock)
                ->color($expiringSoon > 0 ? 'warning' : 'gray'),
        ];
    }
}
