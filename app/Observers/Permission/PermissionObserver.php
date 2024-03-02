<?php

declare(strict_types=1);

namespace App\Observers\Permission;

use App\Models\Permission\Permission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class PermissionObserver implements ShouldQueue
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

    public function created(Permission $permission): void {}

    public function deleted(Permission $permission): void {}

    public function forceDeleted(Permission $permission): void {}

    public function restored(Permission $permission): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(Permission $permission): void {}
}
