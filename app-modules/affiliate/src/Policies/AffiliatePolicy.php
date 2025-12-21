<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Affiliate\Models\Affiliate;
use Misaf\User\Models\User;

final class AffiliatePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-affiliate');
    }

    /**
     * @param User $user
     * @param Affiliate $affiliate
     * @return bool
     */
    public function delete(User $user, Affiliate $affiliate): bool
    {
        return $user->can('delete-affiliate');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-affiliate');
    }

    /**
     * @param User $user
     * @param Affiliate $affiliate
     * @return bool
     */
    public function forceDelete(User $user, Affiliate $affiliate): bool
    {
        return $user->can('force-delete-affiliate');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-affiliate');
    }

    /**
     * @param User $user
     * @param Affiliate $affiliate
     * @return bool
     */
    public function replicate(User $user, Affiliate $affiliate): bool
    {
        return $user->can('replicate-affiliate');
    }

    /**
     * @param User $user
     * @param Affiliate $affiliate
     * @return bool
     */
    public function restore(User $user, Affiliate $affiliate): bool
    {
        return $user->can('restore-affiliate');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-affiliate');
    }

    /**
     * @param User $user
     * @param Affiliate $affiliate
     * @return bool
     */
    public function update(User $user, Affiliate $affiliate): bool
    {
        return $user->can('update-affiliate');
    }

    /**
     * @param User $user
     * @param Affiliate $affiliate
     * @return bool
     */
    public function view(User $user, Affiliate $affiliate): bool
    {
        return $user->can('view-affiliate');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-affiliate');
    }
}
