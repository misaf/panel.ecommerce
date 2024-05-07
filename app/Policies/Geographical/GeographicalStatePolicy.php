<?php

declare(strict_types=1);

namespace App\Policies\Geographical;

use App\Models\Geographical\GeographicalState;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class GeographicalStatePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-geographical-state');
    }

    public function delete(User $user, GeographicalState $geographicalState): bool
    {
        return $user->can('delete-geographical-state');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-geographical-state');
    }

    public function forceDelete(User $user, GeographicalState $geographicalState): bool
    {
        return $user->can('force-delete-geographical-state');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-geographical-state');
    }

    public function replicate(User $user, GeographicalState $geographicalState): bool
    {
        return $user->can('replicate-geographical-state');
    }

    public function restore(User $user, GeographicalState $geographicalState): bool
    {
        return $user->can('restore-geographical-state');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-geographical-state');
    }

    public function update(User $user, GeographicalState $geographicalState): bool
    {
        return $user->can('update-geographical-state');
    }

    public function view(User $user, GeographicalState $geographicalState): bool
    {
        return $user->can('view-geographical-state');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-geographical-state');
    }
}
