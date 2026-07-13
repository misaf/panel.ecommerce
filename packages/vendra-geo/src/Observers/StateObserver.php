<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Misaf\VendraGeo\Models\State;

final class StateObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function deleted(State $state): void
    {
        $state->cities()->delete();
    }
}
