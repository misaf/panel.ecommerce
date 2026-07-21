<?php

declare(strict_types=1);

namespace App\Filament\Account\Widgets;

use App\Filament\Account\Concerns\InteractsWithCurrentAccount;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Models\Subscription;

final class SubscriptionDetail extends StatsOverviewWidget
{
    use InteractsWithCurrentAccount;

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return (new self())->currentAccount()?->activeSubscription() instanceof Subscription;
    }

    protected function getStats(): array
    {
        $subscription = $this->currentAccount()?->activeSubscription();

        if ( ! $subscription instanceof Subscription) {
            return [];
        }

        return [
            Stat::make(__('platform.subscription_status'), __("platform.status_{$subscription->status->value}"))
                ->icon(Heroicon::OutlinedCheckBadge)
                ->color($this->statusColor($subscription->status)),
            Stat::make(__('platform.trial'), $subscription->isOnTrial() && null !== $subscription->trial_ends_at
                ? __('platform.trial_until', ['date' => $subscription->trial_ends_at->format('Y-m-d')])
                : __('platform.no_trial'))
                ->icon(Heroicon::OutlinedClock)
                ->color($subscription->isOnTrial() ? 'info' : 'gray'),
            Stat::make(__('platform.renews_on'), $subscription->ends_at?->format('Y-m-d') ?? __('platform.never'))
                ->icon(Heroicon::OutlinedCalendarDays),
        ];
    }

    private function statusColor(SubscriptionStatus $status): string
    {
        return match ($status) {
            SubscriptionStatus::Active    => 'success',
            SubscriptionStatus::Expired   => 'danger',
            SubscriptionStatus::Cancelled => 'gray',
        };
    }
}
