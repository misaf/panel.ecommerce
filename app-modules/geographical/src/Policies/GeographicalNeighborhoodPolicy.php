<?php

declare(strict_types=1);

namespace Misaf\Geographical\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Geographical\Models\GeographicalNeighborhood;
use Misaf\User\Models\User;

final class GeographicalNeighborhoodPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-geographical-neighborhood');
    }

    /**
     * @param User $user
     * @param GeographicalNeighborhood $geographicalNeighborhood
     * @return bool
     */
    public function delete(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('delete-geographical-neighborhood');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-geographical-neighborhood');
    }

    /**
     * @param User $user
     * @param GeographicalNeighborhood $geographicalNeighborhood
     * @return bool
     */
    public function forceDelete(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('force-delete-geographical-neighborhood');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-geographical-neighborhood');
    }

    /**
     * @param User $user
     * @param GeographicalNeighborhood $geographicalNeighborhood
     * @return bool
     */
    public function replicate(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('replicate-geographical-neighborhood');
    }

    /**
     * @param User $user
     * @param GeographicalNeighborhood $geographicalNeighborhood
     * @return bool
     */
    public function restore(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('restore-geographical-neighborhood');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-geographical-neighborhood');
    }

    /**
     * @param User $user
     * @param GeographicalNeighborhood $geographicalNeighborhood
     * @return bool
     */
    public function update(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('update-geographical-neighborhood');
    }

    /**
     * @param User $user
     * @param GeographicalNeighborhood $geographicalNeighborhood
     * @return bool
     */
    public function view(User $user, GeographicalNeighborhood $geographicalNeighborhood): bool
    {
        return $user->can('view-geographical-neighborhood');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-geographical-neighborhood');
    }
}
