<?php

declare(strict_types=1);

namespace App\Policies\Language;

use App\Models\Language\Language;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class LanguagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-language');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Language $language): bool
    {
        return $user->can('delete-language');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-language');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Language $language): bool
    {
        return $user->can('force-delete-language');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-language');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder-language');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Language $language): bool
    {
        return $user->can('replicate-language');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Language $language): bool
    {
        return $user->can('restore-language');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-language');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Language $language): bool
    {
        return $user->can('update-language');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Language $language): bool
    {
        return $user->can('view-language');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-language');
    }
}
