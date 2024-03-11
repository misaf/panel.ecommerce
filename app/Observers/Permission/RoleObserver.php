<?php

declare(strict_types=1);

namespace App\Observers\Permission;

use App\Models\Permission\Role;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class RoleObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function created(Role $role): void {}

    public function deleted(Role $role): void
    {
        $role->permissions()->delete();
    }

    public function forceDeleted(Role $role): void {}

    public function restored(Role $role): void {}

    public function updated(Role $role): void {}
}
