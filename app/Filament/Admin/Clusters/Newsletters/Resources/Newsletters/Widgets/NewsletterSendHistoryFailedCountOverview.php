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
use Misaf\Newsletter\Models\NewsletterSendHistory;

final class NewsletterSendHistoryFailedCountOverview extends StatsOverviewWidget
{
    public ?Model $record = null;

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = ['sm' => 1];

    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        $failedData = $this->getFailedCountHistoryData();

        return [
            Stat::make('newsletter_send_history_failed_count_stats', Number::format((int) $failedData['total']))
                ->chart($failedData['chart'])
                ->color('danger')
                ->description(__('newsletter::widgets.newsletter_send_history_failed_count_stats_description'))
                ->descriptionIcon('heroicon-m-chart-bar', IconPosition::Before)
                ->label(__('newsletter::widgets.newsletter_send_history_failed_count_stats')),
        ];
    }

    /**
     * @return array{total: int, chart: array<int, int>}
     */
    private function getFailedCountHistoryData(): array
    {
        $weekStart = Carbon::now()->startOfWeek(6);
        $weekEnd = Carbon::now()->endOfWeek();
        $recordId = $this->record?->id;
        $cacheKey = sprintf('newsletter-send-history-failed-count:week:%s-%s', $weekStart->format('Y-W'), $recordId ?? 'all');

        return Cache::tags(['newsletters', 'send_history'])
            ->rememberForever($cacheKey, function () use ($weekStart, $weekEnd, $recordId) {
                $baseQuery = NewsletterSendHistory::when($recordId, fn(Builder $query) => $query->where('newsletter_id', $recordId));

                $trend = Trend::query($baseQuery)
                    ->between($weekStart, $weekEnd)
                    ->dateColumn('completed_at')
                    ->perDay()
                    ->count();

                return [
                    'total' => $baseQuery->sum('failed_count'),
                    'chart' => $trend->map(fn(TrendValue $value) => $value->aggregate)->toArray(),
                ];
            });
    }
}
