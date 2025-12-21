<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Affiliate\Models\AffiliateUser;
use Misaf\User\Models\User;

final class AffiliateUserPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-affiliate-user');
    }

    /**
     * @param User $user
     * @param AffiliateUser $affiliateUser
     * @return bool
     */
    public function delete(User $user, AffiliateUser $affiliateUser): bool
    {
        return $user->can('delete-affiliate-user');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-affiliate-user');
    }

    /**
     * @param User $user
     * @param AffiliateUser $affiliateUser
     * @return bool
     */
    public function forceDelete(User $user, AffiliateUser $affiliateUser): bool
    {
        return $user->can('force-delete-affiliate-user');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-affiliate-user');
    }

    /**
     * @param User $user
     * @param AffiliateUser $affiliateUser
     * @return bool
     */
    public function replicate(User $user, AffiliateUser $affiliateUser): bool
    {
        return $user->can('replicate-affiliate-user');
    }

    /**
     * @param User $user
     * @param AffiliateUser $affiliateUser
     * @return bool
     */
    public function restore(User $user, AffiliateUser $affiliateUser): bool
    {
        return $user->can('restore-affiliate-user');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-affiliate-user');
    }

    /**
     * @param User $user
     * @param AffiliateUser $affiliateUser
     * @return bool
     */
    public function update(User $user, AffiliateUser $affiliateUser): bool
    {
        return false;
    }

    /**
     * @param User $user
     * @param AffiliateUser $affiliateUser
     * @return bool
     */
    public function view(User $user, AffiliateUser $affiliateUser): bool
    {
        return $user->can('view-affiliate-user');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-affiliate-user');
    }
}
