<?php

declare(strict_types=1);

namespace Misaf\Language\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Language\Models\Language;
use Misaf\User\Models\User;

final class LanguagePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-language');
    }

    /**
     * @param User $user
     * @param Language $language
     * @return bool
     */
    public function delete(User $user, Language $language): bool
    {
        return $user->can('delete-language');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-language');
    }

    /**
     * @param User $user
     * @param Language $language
     * @return bool
     */
    public function forceDelete(User $user, Language $language): bool
    {
        return $user->can('force-delete-language');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-language');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder-language');
    }

    /**
     * @param User $user
     * @param Language $language
     * @return bool
     */
    public function replicate(User $user, Language $language): bool
    {
        return $user->can('replicate-language');
    }

    /**
     * @param User $user
     * @param Language $language
     * @return bool
     */
    public function restore(User $user, Language $language): bool
    {
        return $user->can('restore-language');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-language');
    }

    /**
     * @param User $user
     * @param Language $language
     * @return bool
     */
    public function update(User $user, Language $language): bool
    {
        return $user->can('update-language');
    }

    /**
     * @param User $user
     * @param Language $language
     * @return bool
     */
    public function view(User $user, Language $language): bool
    {
        return $user->can('view-language');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-language');
    }
}
