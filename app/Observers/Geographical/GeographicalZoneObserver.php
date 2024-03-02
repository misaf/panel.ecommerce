<?php

declare(strict_types=1);

namespace App\Observers\Geographical;

use App\Jobs\DeleteImageJob;
use App\Models\Geographical\GeographicalCity;
use App\Models\Geographical\GeographicalCountry;
use App\Models\Geographical\GeographicalNeighborhood;
use App\Models\Geographical\GeographicalState;
use App\Models\Geographical\GeographicalZone;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

final class GeographicalZoneObserver implements ShouldQueue
{
    use InteractsWithQueue;

    //public $queue = 'listeners';

    public bool $afterCommit = true;

    public $maxExceptions = 5;

    public $tries = 5;

    public function backoff(): array
    {
        return [1, 5, 10, 30];
    }

    public function created(GeographicalZone $geographicalZone): void {}

    public function deleted(GeographicalZone $geographicalZone): void
    {
        DB::transaction(function () use ($geographicalZone): void {
            $geographicalZone->geographicalCountries()->delete();
            $geographicalZone->geographicalStates()->delete();
            $geographicalZone->geographicalCities()->delete();
            $geographicalZone->geographicalNeighborhoods()->delete();
        });
    }

    public function deleting(GeographicalZone $geographicalZone): void
    {
        if ($geographicalZone->isForceDeleting()) {
            $geographicalZone->geographicalCountries()->each(function ($item): void {
                $item->geographicalStates()->each(function ($item2): void {
                    $item2->geographicalCities()->each(function ($item3): void {
                        $item3->geographicalNeighborhoods()->each(function ($item4): void {
                            // DeleteImageJob::dispatchIf($item4->getRawOriginal('image') !== GeographicalNeighborhood::IMAGE_PATH, $item4);
                        });

                        // DeleteImageJob::dispatchIf($item3->getRawOriginal('image') !== GeographicalCity::IMAGE_PATH, $item3);
                    });

                    // DeleteImageJob::dispatchIf($item2->getRawOriginal('image') !== GeographicalState::IMAGE_PATH, $item2);
                });

                // DeleteImageJob::dispatchIf($item->getRawOriginal('image') !== GeographicalCountry::IMAGE_PATH, $item);
            });
        }
    }

    public function forceDeleted(GeographicalZone $geographicalZone): void {}

    public function restored(GeographicalZone $geographicalZone): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(GeographicalZone $geographicalZone): void {}
}
