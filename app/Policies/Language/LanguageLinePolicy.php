<?php

declare(strict_types=1);

namespace App\Policies\Language;

use App\Models\Language\LanguageLine;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class LanguageLinePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-language-line');
    }

    public function delete(User $user, LanguageLine $languageLine): bool
    {
        return $user->can('delete-language-line');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-language-line');
    }

    public function forceDelete(User $user, LanguageLine $languageLine): bool
    {
        return $user->can('force-delete-language-line');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-language-line');
    }

    public function replicate(User $user, LanguageLine $languageLine): bool
    {
        return $user->can('replicate-language-line');
    }

    public function restore(User $user, LanguageLine $languageLine): bool
    {
        return $user->can('restore-language-line');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-language-line');
    }

    public function update(User $user, LanguageLine $languageLine): bool
    {
        return $user->can('update-language-line');
    }

    public function view(User $user, LanguageLine $languageLine): bool
    {
        return $user->can('view-language-line');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-language-line');
    }
}
