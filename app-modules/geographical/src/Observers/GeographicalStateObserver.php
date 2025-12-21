<?php

declare(strict_types=1);

namespace Misaf\Geographical\Observers;

use App\Jobs\DeleteImageJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Misaf\Geographical\Models\GeographicalState;

final class GeographicalStateObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function deleted(GeographicalState $geographicalState): void
    {
        DB::transaction(function () use ($geographicalState): void {
            $geographicalState->geographicalCities()->delete();
            $geographicalState->geographicalNeighborhoods()->delete();
        });
    }

    public function deleting(GeographicalState $geographicalState): void
    {
        if ($geographicalState->isForceDeleting()) {
            $geographicalState->geographicalCities()->each(function ($item): void {
                $item->geographicalNeighborhoods()->each(function ($item2): void {
                    // DeleteImageJob::dispatchIf($item2->getRawOriginal('image') !== GeographicalNeighborhood::IMAGE_PATH, $item2);
                });

                // DeleteImageJob::dispatchIf($item->getRawOriginal('image') !== GeographicalCity::IMAGE_PATH, $item);
            });
        }
    }
}
