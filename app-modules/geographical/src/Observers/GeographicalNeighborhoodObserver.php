<?php

declare(strict_types=1);

namespace Misaf\Geographical\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class GeographicalNeighborhoodObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;
}
