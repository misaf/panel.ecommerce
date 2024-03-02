<?php

declare(strict_types=1);

namespace App\Observers\Permission;

use App\Models\Permission\Role;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class RoleObserver implements ShouldQueue
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

    public function created(Role $role): void {}

    public function deleted(Role $role): void
    {
        $role->permissions()->delete();
    }

    public function forceDeleted(Role $role): void {}

    public function restored(Role $role): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(Role $role): void {}
}
