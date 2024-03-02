<?php

declare(strict_types=1);

namespace App\Observers\Geographical;

use App\Models\Geographical\GeographicalNeighborhood;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class GeographicalNeighborhoodObserver implements ShouldQueue
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

    public function created(GeographicalNeighborhood $geographicalNeighborhood): void {}

    public function deleted(GeographicalNeighborhood $geographicalNeighborhood): void {}

    public function forceDeleted(GeographicalNeighborhood $geographicalNeighborhood): void {}

    public function restored(GeographicalNeighborhood $geographicalNeighborhood): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(GeographicalNeighborhood $geographicalNeighborhood): void {}
}
