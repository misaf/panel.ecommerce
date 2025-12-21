<?php

declare(strict_types=1);

namespace Misaf\Permission\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class PermissionObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;
}
