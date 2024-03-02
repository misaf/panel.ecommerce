<?php

declare(strict_types=1);

namespace App\Policies\Language;

use App\Models\Language\Language;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class LanguagePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-language');
    }

    public function delete(User $user, Language $language): bool
    {
        return $user->can('delete-language');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-language');
    }

    public function forceDelete(User $user, Language $language): bool
    {
        return $user->can('force-delete-language');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-language');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder-language');
    }

    public function replicate(User $user, Language $language): bool
    {
        return $user->can('replicate-language');
    }

    public function restore(User $user, Language $language): bool
    {
        return $user->can('restore-language');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-language');
    }

    public function update(User $user, Language $language): bool
    {
        return $user->can('update-language');
    }

    public function view(User $user, Language $language): bool
    {
        return $user->can('view-language');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-language');
    }
}
