<?php

declare(strict_types=1);

namespace Misaf\Currency\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Currency\Models\CurrencyCategory;
use Misaf\User\Models\User;

final class CurrencyCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-currency-category');
    }

    /**
     * @param User $user
     * @param CurrencyCategory $currencyCategory
     * @return bool
     */
    public function delete(User $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can('delete-currency-category');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-currency-category');
    }

    /**
     * @param User $user
     * @param CurrencyCategory $currencyCategory
     * @return bool
     */
    public function forceDelete(User $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can('force-delete-currency-category');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-currency-category');
    }

    /**
     * @param User $user
     * @param CurrencyCategory $currencyCategory
     * @return bool
     */
    public function replicate(User $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can('replicate-currency-category');
    }

    /**
     * @param User $user
     * @param CurrencyCategory $currencyCategory
     * @return bool
     */
    public function restore(User $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can('restore-currency-category');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-currency-category');
    }

    /**
     * @param User $user
     * @param CurrencyCategory $currencyCategory
     * @return bool
     */
    public function update(User $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can('update-currency-category');
    }

    /**
     * @param User $user
     * @param CurrencyCategory $currencyCategory
     * @return bool
     */
    public function view(User $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can('view-currency-category');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-currency-category');
    }
}
