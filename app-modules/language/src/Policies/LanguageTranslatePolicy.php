<?php

declare(strict_types=1);

namespace Misaf\Language\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Language\Models\LanguageTranslate;
use Misaf\User\Models\User;

final class LanguageTranslatePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-language-translate');
    }

    /**
     * @param User $user
     * @param LanguageTranslate $languageTranslate
     * @return bool
     */
    public function delete(User $user, LanguageTranslate $languageTranslate): bool
    {
        return $user->can('delete-language-translate');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-language-translate');
    }

    /**
     * @param User $user
     * @param LanguageTranslate $languageTranslate
     * @return bool
     */
    public function forceDelete(User $user, LanguageTranslate $languageTranslate): bool
    {
        return $user->can('force-delete-language-translate');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-language-translate');
    }

    /**
     * @param User $user
     * @param LanguageTranslate $languageTranslate
     * @return bool
     */
    public function replicate(User $user, LanguageTranslate $languageTranslate): bool
    {
        return $user->can('replicate-language-translate');
    }

    /**
     * @param User $user
     * @param LanguageTranslate $languageTranslate
     * @return bool
     */
    public function restore(User $user, LanguageTranslate $languageTranslate): bool
    {
        return $user->can('restore-language-translate');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-language-translate');
    }

    /**
     * @param User $user
     * @param LanguageTranslate $languageTranslate
     * @return bool
     */
    public function update(User $user, LanguageTranslate $languageTranslate): bool
    {
        return $user->can('update-language-translate');
    }

    /**
     * @param User $user
     * @param LanguageTranslate $languageTranslate
     * @return bool
     */
    public function view(User $user, LanguageTranslate $languageTranslate): bool
    {
        return $user->can('view-language-translate');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-language-translate');
    }
}
