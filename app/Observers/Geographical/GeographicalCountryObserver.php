<?php

declare(strict_types=1);

namespace App\Observers\Geographical;

use App\Jobs\DeleteImageJob;
use App\Models\Geographical\GeographicalCity;
use App\Models\Geographical\GeographicalCountry;
use App\Models\Geographical\GeographicalNeighborhood;
use App\Models\Geographical\GeographicalState;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

final class GeographicalCountryObserver implements ShouldQueue
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

    public function created(GeographicalCountry $geographicalCountry): void {}

    public function deleted(GeographicalCountry $geographicalCountry): void
    {
        DB::transaction(function () use ($geographicalCountry): void {
            $geographicalCountry->geographicalStates()->delete();
            $geographicalCountry->geographicalCities()->delete();
            $geographicalCountry->geographicalNeighborhoods()->delete();
        });
    }

    public function deleting(GeographicalCountry $geographicalCountry): void
    {
        if ($geographicalCountry->isForceDeleting()) {
            $geographicalCountry->geographicalStates()->each(function ($item): void {
                $item->geographicalCities()->each(function ($item2): void {
                    $item2->geographicalNeighborhoods()->each(function ($item3): void {
                        // DeleteImageJob::dispatchIf($item3->getRawOriginal('image') !== GeographicalNeighborhood::IMAGE_PATH, $item3);
                    });

                    // DeleteImageJob::dispatchIf($item2->getRawOriginal('image') !== GeographicalCity::IMAGE_PATH, $item2);
                });

                // DeleteImageJob::dispatchIf($item->getRawOriginal('image') !== GeographicalState::IMAGE_PATH, $item);
            });
        }
    }

    public function forceDeleted(GeographicalCountry $geographicalCountry): void {}

    public function restored(GeographicalCountry $geographicalCountry): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(GeographicalCountry $geographicalCountry): void {}
}
