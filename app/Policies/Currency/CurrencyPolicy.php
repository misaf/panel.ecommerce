<?php

declare(strict_types=1);

namespace App\Policies\Currency;

use App\Models\Currency\Currency;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class CurrencyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-currency');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Currency $currency): bool
    {
        return $user->can('delete-currency');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-currency');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Currency $currency): bool
    {
        return $user->can('force-delete-currency');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-currency');
    }

    /**
     * Determine whether the user can reorder the model.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder-currency');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Currency $currency): bool
    {
        return $user->can('replicate-currency');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Currency $currency): bool
    {
        return $user->can('restore-currency');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-currency');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Currency $currency): bool
    {
        return $user->can('update-currency');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Currency $currency): bool
    {
        return $user->can('view-currency');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-currency');
    }
}
