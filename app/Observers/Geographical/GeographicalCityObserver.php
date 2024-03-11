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

    public bool $afterCommit = true;

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

    public function updated(GeographicalCity $geographicalCity): void {}
}
