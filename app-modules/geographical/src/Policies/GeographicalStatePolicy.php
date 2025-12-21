<?php

declare(strict_types=1);

namespace Misaf\Geographical\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Geographical\Models\GeographicalState;
use Misaf\User\Models\User;

final class GeographicalStatePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-geographical-state');
    }

    /**
     * @param User $user
     * @param GeographicalState $geographicalState
     * @return bool
     */
    public function delete(User $user, GeographicalState $geographicalState): bool
    {
        return $user->can('delete-geographical-state');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-geographical-state');
    }

    /**
     * @param User $user
     * @param GeographicalState $geographicalState
     * @return bool
     */
    public function forceDelete(User $user, GeographicalState $geographicalState): bool
    {
        return $user->can('force-delete-geographical-state');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-geographical-state');
    }

    /**
     * @param User $user
     * @param GeographicalState $geographicalState
     * @return bool
     */
    public function replicate(User $user, GeographicalState $geographicalState): bool
    {
        return $user->can('replicate-geographical-state');
    }

    /**
     * @param User $user
     * @param GeographicalState $geographicalState
     * @return bool
     */
    public function restore(User $user, GeographicalState $geographicalState): bool
    {
        return $user->can('restore-geographical-state');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-geographical-state');
    }

    /**
     * @param User $user
     * @param GeographicalState $geographicalState
     * @return bool
     */
    public function update(User $user, GeographicalState $geographicalState): bool
    {
        return $user->can('update-geographical-state');
    }

    /**
     * @param User $user
     * @param GeographicalState $geographicalState
     * @return bool
     */
    public function view(User $user, GeographicalState $geographicalState): bool
    {
        return $user->can('view-geographical-state');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-geographical-state');
    }
}
