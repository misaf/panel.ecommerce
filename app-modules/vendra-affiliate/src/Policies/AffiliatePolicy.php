<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraAffiliate\Models\Affiliate;

final class AffiliatePolicy
{
    use HandlesAuthorization;

    /**
     * @param Authorizable $user
     * @return bool
     */
    public function create(Authorizable $user): bool
    {
        return $user->can('create-affiliate');
    }

    /**
     * @param Authorizable $user
     * @param Affiliate $affiliate
     * @return bool
     */
    public function delete(Authorizable $user, Affiliate $affiliate): bool
    {
        return $user->can('delete-affiliate');
    }

    /**
     * @param Authorizable $user
     * @return bool
     */
    public function deleteAny(Authorizable $user): bool
    {
        return $user->can('delete-any-affiliate');
    }

    /**
     * @param Authorizable $user
     * @param Affiliate $affiliate
     * @return bool
     */
    public function forceDelete(Authorizable $user, Affiliate $affiliate): bool
    {
        return $user->can('force-delete-affiliate');
    }

    /**
     * @param Authorizable $user
     * @return bool
     */
    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can('force-delete-any-affiliate');
    }

    /**
     * @param Authorizable $user
     * @param Affiliate $affiliate
     * @return bool
     */
    public function replicate(Authorizable $user, Affiliate $affiliate): bool
    {
        return $user->can('replicate-affiliate');
    }

    /**
     * @param Authorizable $user
     * @param Affiliate $affiliate
     * @return bool
     */
    public function restore(Authorizable $user, Affiliate $affiliate): bool
    {
        return $user->can('restore-affiliate');
    }

    /**
     * @param Authorizable $user
     * @return bool
     */
    public function restoreAny(Authorizable $user): bool
    {
        return $user->can('restore-any-affiliate');
    }

    /**
     * @param Authorizable $user
     * @param Affiliate $affiliate
     * @return bool
     */
    public function update(Authorizable $user, Affiliate $affiliate): bool
    {
        return $user->can('update-affiliate');
    }

    /**
     * @param Authorizable $user
     * @param Affiliate $affiliate
     * @return bool
     */
    public function view(Authorizable $user, Affiliate $affiliate): bool
    {
        return $user->can('view-affiliate');
    }

    /**
     * @param Authorizable $user
     * @return bool
     */
    public function viewAny(Authorizable $user): bool
    {
        return $user->can('view-any-affiliate');
    }
}
