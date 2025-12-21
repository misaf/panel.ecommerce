<?php

declare(strict_types=1);

namespace Misaf\Geographical\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Geographical\Models\GeographicalZone;
use Misaf\User\Models\User;

final class GeographicalZonePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-geographical-zone');
    }

    /**
     * @param User $user
     * @param GeographicalZone $geographicalZone
     * @return bool
     */
    public function delete(User $user, GeographicalZone $geographicalZone): bool
    {
        return $user->can('delete-geographical-zone');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-geographical-zone');
    }

    /**
     * @param User $user
     * @param GeographicalZone $geographicalZone
     * @return bool
     */
    public function forceDelete(User $user, GeographicalZone $geographicalZone): bool
    {
        return $user->can('force-delete-geographical-zone');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-geographical-zone');
    }

    /**
     * @param User $user
     * @param GeographicalZone $geographicalZone
     * @return bool
     */
    public function replicate(User $user, GeographicalZone $geographicalZone): bool
    {
        return $user->can('replicate-geographical-zone');
    }

    /**
     * @param User $user
     * @param GeographicalZone $geographicalZone
     * @return bool
     */
    public function restore(User $user, GeographicalZone $geographicalZone): bool
    {
        return $user->can('restore-geographical-zone');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-geographical-zone');
    }

    /**
     * @param User $user
     * @param GeographicalZone $geographicalZone
     * @return bool
     */
    public function update(User $user, GeographicalZone $geographicalZone): bool
    {
        return $user->can('update-geographical-zone');
    }

    /**
     * @param User $user
     * @param GeographicalZone $geographicalZone
     * @return bool
     */
    public function view(User $user, GeographicalZone $geographicalZone): bool
    {
        return $user->can('view-geographical-zone');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-geographical-zone');
    }
}
