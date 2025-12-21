<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Widgets;

use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Number;
use Misaf\Newsletter\Models\NewsletterSubscriber;

final class NewsletterSubscribedUsersOverview extends StatsOverviewWidget
{
    public ?Model $record = null;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = ['sm' => 1];

    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        $subscribedData = $this->getSubscribedUsersData();

        return [
            Stat::make('subscribed_users', Number::format($subscribedData['total']))
                ->chart($subscribedData['chart'])
                ->color('success')
                ->description(__('newsletter/widgets.subscribed_users_description'))
                ->descriptionIcon('heroicon-m-chart-bar', IconPosition::Before)
                ->label(__('newsletter/widgets.subscribed_users')),
        ];
    }

    private function getSubscribedUsersData(): array
    {
        $weekStart = Carbon::now()->startOfWeek(6);
        $weekEnd = Carbon::now()->endOfWeek();
        $recordId = $this->record?->id;
        $cacheKey = sprintf('newsletter-subscribed-users:week:%s-%s', $weekStart->format('Y-W'), $recordId ?? 'all');

        return Cache::tags(['newsletters', 'subscribers'])
            ->rememberForever($cacheKey, function () use ($weekStart, $weekEnd, $recordId) {
                $baseQuery = NewsletterSubscriber::when($recordId, fn(Builder $query) => $query->whereRelation('subscribedNewsletters', 'newsletter_id', $recordId));

                $trend = Trend::query($baseQuery)
                    ->between($weekStart, $weekEnd)
                    ->perDay()
                    ->count();

                return [
                    'total' => $baseQuery->count(),
                    'chart' => $trend->map(fn(TrendValue $value) => $value->aggregate)->toArray(),
                ];
            });
    }
}
