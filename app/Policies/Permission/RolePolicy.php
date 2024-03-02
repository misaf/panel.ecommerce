<?php

declare(strict_types=1);

namespace App\Policies\Permission;

use App\Models\Permission\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class RolePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-role');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->can('delete-role');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-role');
    }

    public function forceDelete(User $user, Role $role): bool
    {
        return $user->can('force-delete-role');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-role');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder-role');
    }

    public function replicate(User $user, Role $role): bool
    {
        return $user->can('replicate-role');
    }

    public function restore(User $user, Role $role): bool
    {
        return $user->can('restore-role');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-role');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->can('update-role');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->can('view-role');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-role');
    }
}
