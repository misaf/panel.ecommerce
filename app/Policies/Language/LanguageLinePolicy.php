<?php

declare(strict_types=1);

namespace App\Policies\Language;

use App\Models\Language\LanguageLine;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class LanguageLinePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-language-line');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LanguageLine $languageLine): bool
    {
        return $user->can('delete-language-line');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-language-line');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LanguageLine $languageLine): bool
    {
        return $user->can('force-delete-language-line');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-language-line');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, LanguageLine $languageLine): bool
    {
        return $user->can('replicate-language-line');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LanguageLine $languageLine): bool
    {
        return $user->can('restore-language-line');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-language-line');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LanguageLine $languageLine): bool
    {
        return $user->can('update-language-line');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LanguageLine $languageLine): bool
    {
        return $user->can('view-language-line');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-language-line');
    }
}
