<?php

declare(strict_types=1);

namespace Misaf\Geographical\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Geographical\Models\GeographicalCountry;
use Misaf\User\Models\User;

final class GeographicalCountryPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-geographical-country');
    }

    /**
     * @param User $user
     * @param GeographicalCountry $geographicalCountry
     * @return bool
     */
    public function delete(User $user, GeographicalCountry $geographicalCountry): bool
    {
        return $user->can('delete-geographical-country');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-geographical-country');
    }

    /**
     * @param User $user
     * @param GeographicalCountry $geographicalCountry
     * @return bool
     */
    public function forceDelete(User $user, GeographicalCountry $geographicalCountry): bool
    {
        return $user->can('force-delete-geographical-country');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-geographical-country');
    }

    /**
     * @param User $user
     * @param GeographicalCountry $geographicalCountry
     * @return bool
     */
    public function replicate(User $user, GeographicalCountry $geographicalCountry): bool
    {
        return $user->can('replicate-geographical-country');
    }

    /**
     * @param User $user
     * @param GeographicalCountry $geographicalCountry
     * @return bool
     */
    public function restore(User $user, GeographicalCountry $geographicalCountry): bool
    {
        return $user->can('restore-geographical-country');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-geographical-country');
    }

    /**
     * @param User $user
     * @param GeographicalCountry $geographicalCountry
     * @return bool
     */
    public function update(User $user, GeographicalCountry $geographicalCountry): bool
    {
        return $user->can('update-geographical-country');
    }

    /**
     * @param User $user
     * @param GeographicalCountry $geographicalCountry
     * @return bool
     */
    public function view(User $user, GeographicalCountry $geographicalCountry): bool
    {
        return $user->can('view-geographical-country');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-geographical-country');
    }
}
