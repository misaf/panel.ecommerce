<?php

declare(strict_types=1);

namespace App\Filament\Account\Widgets;

use App\Filament\Account\Concerns\InteractsWithCurrentAccount;
use App\Filament\Concerns\BuildsDailyTrend;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraTenant\Models\Tenant;

final class AccountOverview extends StatsOverviewWidget
{
    use BuildsDailyTrend;
    use InteractsWithCurrentAccount;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $account = $this->currentAccount();

        if (null === $account) {
            return [];
        }

        $subscription = $account->activeSubscription();
        $used = $account->tenants()->count();

        $planName = __('platform.no_plan');
        $max = null;

        if ($subscription instanceof Subscription && null !== $subscription->plan) {
            $planName = $subscription->plan->name;
            $max = $subscription->plan->max_websites;
        }

        return [
            Stat::make(__('platform.plan'), $planName)
                ->icon(Heroicon::OutlinedRectangleStack)
                ->color($subscription instanceof Subscription ? 'primary' : 'gray'),
            Stat::make(__('platform.websites'), null === $max ? (string) $used : "{$used} / {$max}")
                ->icon(Heroicon::OutlinedGlobeAlt)
                ->chart($this->dailyTrend(Tenant::query()->where('account_id', $account->getKey()))),
        ];
    }
}
