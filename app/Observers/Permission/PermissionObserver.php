<?php

declare(strict_types=1);

namespace App\Observers\Permission;

use App\Models\Permission\Permission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class PermissionObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function created(Permission $permission): void {}

    public function deleted(Permission $permission): void {}

    public function forceDeleted(Permission $permission): void {}

    public function restored(Permission $permission): void {}

    public function updated(Permission $permission): void {}
}
