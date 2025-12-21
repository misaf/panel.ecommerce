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

final class NewsletterUnsubscribedUsersOverview extends StatsOverviewWidget
{
    public ?Model $record = null;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = ['sm' => 1];

    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        $unsubscribedData = $this->getUnsubscribedUsersData();

        return [
            Stat::make('unsubscribed_users', Number::format($unsubscribedData['total']))
                ->chart($unsubscribedData['chart'])
                ->color('danger')
                ->description(__('newsletter/widgets.unsubscribed_users_description'))
                ->descriptionIcon('heroicon-m-chart-bar', IconPosition::Before)
                ->label(__('newsletter/widgets.unsubscribed_users')),
        ];
    }

    private function getUnsubscribedUsersData(): array
    {
        $weekStart = Carbon::now()->startOfWeek(6);
        $weekEnd = Carbon::now()->endOfWeek();
        $recordId = $this->record?->id;
        $cacheKey = sprintf('newsletter-unsubscribed-users:week:%s-%s', $weekStart->format('Y-W'), $recordId ?? 'all');

        return Cache::tags(['newsletters', 'subscribers'])
            ->rememberForever($cacheKey, function () use ($weekStart, $weekEnd, $recordId) {
                $baseQuery = NewsletterSubscriber::when($recordId, fn(Builder $query) => $query->whereRelation('unsubscribedNewsletters', 'newsletter_id', $recordId));

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
