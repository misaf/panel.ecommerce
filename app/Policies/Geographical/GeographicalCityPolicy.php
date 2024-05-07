<?php

declare(strict_types=1);

namespace App\Policies\Geographical;

use App\Models\Geographical\GeographicalCity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class GeographicalCityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-geographical-city');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('delete-geographical-city');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-geographical-city');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('force-delete-geographical-city');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-geographical-city');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('replicate-geographical-city');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('restore-geographical-city');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-geographical-city');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('update-geographical-city');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('view-geographical-city');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-geographical-city');
    }
}
