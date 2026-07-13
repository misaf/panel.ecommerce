<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraAffiliate\Models\AffiliateUser;

final class AffiliateUserPolicy
{
    use HandlesAuthorization;

    /**
     * @param Authorizable $user
     * @return bool
     */
    public function create(Authorizable $user): bool
    {
        return $user->can('create-affiliate-user');
    }

    /**
     * @param Authorizable $user
     * @param AffiliateUser $affiliateUser
     * @return bool
     */
    public function delete(Authorizable $user, AffiliateUser $affiliateUser): bool
    {
        return $user->can('delete-affiliate-user');
    }

    /**
     * @param Authorizable $user
     * @return bool
     */
    public function deleteAny(Authorizable $user): bool
    {
        return $user->can('delete-any-affiliate-user');
    }

    /**
     * @param Authorizable $user
     * @param AffiliateUser $affiliateUser
     * @return bool
     */
    public function forceDelete(Authorizable $user, AffiliateUser $affiliateUser): bool
    {
        return $user->can('force-delete-affiliate-user');
    }

    /**
     * @param Authorizable $user
     * @return bool
     */
    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can('force-delete-any-affiliate-user');
    }

    /**
     * @param Authorizable $user
     * @param AffiliateUser $affiliateUser
     * @return bool
     */
    public function replicate(Authorizable $user, AffiliateUser $affiliateUser): bool
    {
        return $user->can('replicate-affiliate-user');
    }

    /**
     * @param Authorizable $user
     * @param AffiliateUser $affiliateUser
     * @return bool
     */
    public function restore(Authorizable $user, AffiliateUser $affiliateUser): bool
    {
        return $user->can('restore-affiliate-user');
    }

    /**
     * @param Authorizable $user
     * @return bool
     */
    public function restoreAny(Authorizable $user): bool
    {
        return $user->can('restore-any-affiliate-user');
    }

    /**
     * @param Authorizable $user
     * @param AffiliateUser $affiliateUser
     * @return bool
     */
    public function update(Authorizable $user, AffiliateUser $affiliateUser): bool
    {
        return false;
    }

    /**
     * @param Authorizable $user
     * @param AffiliateUser $affiliateUser
     * @return bool
     */
    public function view(Authorizable $user, AffiliateUser $affiliateUser): bool
    {
        return $user->can('view-affiliate-user');
    }

    /**
     * @param Authorizable $user
     * @return bool
     */
    public function viewAny(Authorizable $user): bool
    {
        return $user->can('view-any-affiliate-user');
    }
}
