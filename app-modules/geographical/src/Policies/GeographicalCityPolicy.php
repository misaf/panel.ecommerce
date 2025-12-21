<?php

declare(strict_types=1);

namespace Misaf\Geographical\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Geographical\Models\GeographicalCity;
use Misaf\User\Models\User;

final class GeographicalCityPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-geographical-city');
    }

    /**
     * @param User $user
     * @param GeographicalCity $geographicalCity
     * @return bool
     */
    public function delete(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('delete-geographical-city');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-geographical-city');
    }

    /**
     * @param User $user
     * @param GeographicalCity $geographicalCity
     * @return bool
     */
    public function forceDelete(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('force-delete-geographical-city');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-geographical-city');
    }

    /**
     * @param User $user
     * @param GeographicalCity $geographicalCity
     * @return bool
     */
    public function replicate(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('replicate-geographical-city');
    }

    /**
     * @param User $user
     * @param GeographicalCity $geographicalCity
     * @return bool
     */
    public function restore(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('restore-geographical-city');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-geographical-city');
    }

    /**
     * @param User $user
     * @param GeographicalCity $geographicalCity
     * @return bool
     */
    public function update(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('update-geographical-city');
    }

    /**
     * @param User $user
     * @param GeographicalCity $geographicalCity
     * @return bool
     */
    public function view(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('view-geographical-city');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-geographical-city');
    }
}
