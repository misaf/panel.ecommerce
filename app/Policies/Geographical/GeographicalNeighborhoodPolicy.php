<?php

declare(strict_types=1);

namespace App\Policies\Geographical;

use App\Models\Geographical\GeographicalNeighborhood;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class GeographicalNeighborhoodPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-geographical-neighborhood');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('delete-geographical-neighborhood');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-geographical-neighborhood');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('force-delete-geographical-neighborhood');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-geographical-neighborhood');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('replicate-geographical-neighborhood');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('restore-geographical-neighborhood');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-geographical-neighborhood');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('update-geographical-neighborhood');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('view-geographical-neighborhood');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-geographical-neighborhood');
    }
}
