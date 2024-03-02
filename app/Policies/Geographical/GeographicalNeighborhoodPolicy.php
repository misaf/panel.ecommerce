<?php

declare(strict_types=1);

namespace App\Policies\Geographical;

use App\Models\Geographical\GeographicalNeighborhood;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class GeographicalNeighborhoodPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-geographical-neighborhood');
    }

    public function delete(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('delete-geographical-neighborhood');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-geographical-neighborhood');
    }

    public function forceDelete(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('force-delete-geographical-neighborhood');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-geographical-neighborhood');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder-geographical-neighborhood');
    }

    public function replicate(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('replicate-geographical-neighborhood');
    }

    public function restore(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('restore-geographical-neighborhood');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-geographical-neighborhood');
    }

    public function update(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('update-geographical-neighborhood');
    }

    public function view(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('view-geographical-neighborhood');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-geographical-neighborhood');
    }
}
