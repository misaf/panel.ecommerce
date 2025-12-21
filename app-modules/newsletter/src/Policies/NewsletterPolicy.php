<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\User\Models\User;

final class NewsletterPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-newsletter');
    }

    /**
     * @param User $user
     * @param Newsletter $newsletter
     * @return bool
     */
    public function delete(User $user, Newsletter $newsletter): bool
    {
        return $user->can('delete-newsletter');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-newsletter');
    }

    /**
     * @param User $user
     * @param Newsletter $newsletter
     * @return bool
     */
    public function forceDelete(User $user, Newsletter $newsletter): bool
    {
        return $user->can('force-delete-newsletter');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-newsletter');
    }

    /**
     * @param User $user
     * @param Newsletter $newsletter
     * @return bool
     */
    public function replicate(User $user, Newsletter $newsletter): bool
    {
        return $user->can('replicate-newsletter');
    }

    /**
     * @param User $user
     * @param Newsletter $newsletter
     * @return bool
     */
    public function restore(User $user, Newsletter $newsletter): bool
    {
        return $user->can('restore-newsletter');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-newsletter');
    }

    /**
     * @param User $user
     * @param Newsletter $newsletter
     * @return bool
     */
    public function update(User $user, Newsletter $newsletter): bool
    {
        return $user->can('update-newsletter');
    }

    /**
     * @param User $user
     * @param Newsletter $newsletter
     * @return bool
     */
    public function view(User $user, Newsletter $newsletter): bool
    {
        return $user->can('view-newsletter');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-newsletter');
    }
}
