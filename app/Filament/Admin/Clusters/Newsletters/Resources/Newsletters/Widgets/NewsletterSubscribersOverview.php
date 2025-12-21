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

final class NewsletterSubscribersOverview extends StatsOverviewWidget
{
    public ?Model $record = null;

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = ['sm' => 1];

    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        $subscribersData = $this->getSusbcribersData();

        return [
            Stat::make('newsletter_subscriber_stats', Number::format($subscribersData['total']))
                ->chart($subscribersData['chart'])
                ->color('primary')
                ->description(__('newsletter/widgets.newsletter_subscriber_stats_description'))
                ->descriptionIcon('heroicon-m-chart-bar', IconPosition::Before)
                ->label(__('newsletter/widgets.newsletter_subscriber_stats')),
        ];
    }

    private function getSusbcribersData(): array
    {
        $weekStart = Carbon::now()->startOfWeek(6);
        $weekEnd = Carbon::now()->endOfWeek();
        $recordId = $this->record?->id;
        $cacheKey = sprintf('newsletter-subscribers:week:%s-%s', $weekStart->format('Y-W'), $recordId ?? 'all');

        return Cache::tags(['newsletters', 'subscribers'])
            ->rememberForever($cacheKey, function () use ($weekStart, $weekEnd, $recordId) {
                $baseQuery = NewsletterSubscriber::when($recordId, fn(Builder $query) => $query->whereRelation('newsletters', 'newsletter_id', $recordId));

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
