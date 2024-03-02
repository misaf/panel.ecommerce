<?php

declare(strict_types=1);

namespace App\Observers\Geographical;

use App\Jobs\DeleteImageJob;
use App\Models\Geographical\GeographicalCity;
use App\Models\Geographical\GeographicalNeighborhood;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class GeographicalCityObserver implements ShouldQueue
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

    public function created(GeographicalCity $geographicalCity): void {}

    public function deleted(GeographicalCity $geographicalCity): void
    {
        $geographicalCity->geographicalNeighborhoods()->delete();
    }

    public function deleting(GeographicalCity $geographicalCity): void
    {
        if ($geographicalCity->isForceDeleting()) {
            $geographicalCity->geographicalNeighborhoods()->each(function ($item): void {
                // DeleteImageJob::dispatchIf($item->getRawOriginal('image') !== GeographicalNeighborhood::IMAGE_PATH, $item);
            });
        }
    }

    public function forceDeleted(GeographicalCity $geographicalCity): void {}

    public function restored(GeographicalCity $geographicalCity): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(GeographicalCity $geographicalCity): void {}
}
