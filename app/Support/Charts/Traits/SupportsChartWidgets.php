<?php

declare(strict_types=1);

namespace App\Support\Charts\Traits;

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

trait SupportsChartWidgets
{
    /**
     * @return string[] // ['2025-05-25', '2025-05-26', ...]
     */
    protected function generateDateRange(Carbon $start, Carbon $end): array
    {
        $dates = [];

        // Ensure both are in UTC
        $current = $start->copy()->startOfDay();
        $end = $end->copy()->endOfDay();

        while ($current->lte($end)) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }

        return $dates;
    }

    /**
     * @param  string[]  $dates
     * @return string[]
     */
    protected function generateLabels(array $dates): array
    {
        return array_map(
            fn(string $date) => 'fa' === app()->getLocale()
                ? Jalalian::fromCarbon(Carbon::parse($date))->format('j F')
                : Carbon::parse($date)->translatedFormat('j F'),
            $dates,
        );
    }

    /**
     * @param  string[]  $dates
     * @param  array<string, array<string, int>>  $dataByDate
     * @return array<string, mixed>
     */
    protected function buildDataset(string $field, string $label, string $color, array $dates, array $dataByDate): array
    {
        return [
            'label' => $label,
            'data'  => array_map(
                fn(string $date) => (int) ($dataByDate[$date][$field] ?? 0),
                $dates,
            ),
            'fill'        => false,
            'tension'     => 0.4,
            'borderWidth' => 2,
            'borderColor' => $color,
        ];
    }
}
