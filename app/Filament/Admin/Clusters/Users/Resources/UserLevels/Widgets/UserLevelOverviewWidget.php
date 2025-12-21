<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserLevels\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

final class UserLevelOverviewWidget extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    public ?Model $record = null;

    /**
     * @var int|string|array<string, int|null>
     */
    protected int|string|array $columnSpan = [
        'sm' => 1,
    ];

    protected function getColumns(): int
    {
        return 1;
    }

    protected static ?int $sort = 1;

    public static function isDiscovered(): bool
    {
        return true;
    }

    public static function canView(): bool
    {
        return true;
    }

    /**
     * @return array<int, Stat>
     */
    protected function getStats(): array
    {
        $startDate = isset($this->pageFilters['start_date'])
            ? Carbon::parse($this->pageFilters['start_date'])
            : now()->subDays(6)->startOfDay();

        $endDate = isset($this->pageFilters['end_date'])
            ? Carbon::parse($this->pageFilters['end_date'])
            : now()->endOfDay();

        $today = now()->toDateString();

        $dates = collect();
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dates->push($currentDate->toDateString());
            $currentDate->addDay();
        }

        $userRakeAmount = $this->record->latestUserLevelHistory->userLevel->name ?? 0;

        $userRakeStats = $dates->mapWithKeys(function ($date) {
            $sum = Cache::store('user_rake')->get("user-level-stats:user:{$this->record->id}:daily:{$date}", 0);

            return [$date => $sum];
        });

        $userRake = Stat::make('user_level_stats', $userRakeAmount)
            ->label(__('user-level::widgets.user_level_stats'))
            ->description(__('user-level::widgets.user_level_stats_description'))
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart($userRakeStats->toArray())
            ->color('primary');

        return [$userRake];
    }
}
