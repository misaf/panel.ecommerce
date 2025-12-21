<?php

declare(strict_types=1);

namespace Misaf\Currency\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Currency\Models\Currency;
use Misaf\User\Models\User;

final class CurrencyPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-currency');
    }

    /**
     * @param User $user
     * @param Currency $currency
     * @return bool
     */
    public function delete(User $user, Currency $currency): bool
    {
        return $user->can('delete-currency');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-currency');
    }

    /**
     * @param User $user
     * @param Currency $currency
     * @return bool
     */
    public function forceDelete(User $user, Currency $currency): bool
    {
        return $user->can('force-delete-currency');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-currency');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder-currency');
    }

    /**
     * @param User $user
     * @param Currency $currency
     * @return bool
     */
    public function replicate(User $user, Currency $currency): bool
    {
        return $user->can('replicate-currency');
    }

    /**
     * @param User $user
     * @param Currency $currency
     * @return bool
     */
    public function restore(User $user, Currency $currency): bool
    {
        return $user->can('restore-currency');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-currency');
    }

    /**
     * @param User $user
     * @param Currency $currency
     * @return bool
     */
    public function update(User $user, Currency $currency): bool
    {
        return $user->can('update-currency');
    }

    /**
     * @param User $user
     * @param Currency $currency
     * @return bool
     */
    public function view(User $user, Currency $currency): bool
    {
        return $user->can('view-currency');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-currency');
    }
}
