<?php

declare(strict_types=1);

namespace Misaf\Geographical\Observers;

use App\Jobs\DeleteImageJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Misaf\Geographical\Models\GeographicalCity;

final class GeographicalCityObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

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
}
