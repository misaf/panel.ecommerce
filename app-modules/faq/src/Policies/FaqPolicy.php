<?php

declare(strict_types=1);

namespace Misaf\Faq\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Faq\Models\Faq;
use Misaf\User\Models\User;

final class FaqPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-faq');
    }

    /**
     * @param User $user
     * @param Faq $faq
     * @return bool
     */
    public function delete(User $user, Faq $faq): bool
    {
        return $user->can('delete-faq');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-faq');
    }

    /**
     * @param User $user
     * @param Faq $faq
     * @return bool
     */
    public function forceDelete(User $user, Faq $faq): bool
    {
        return $user->can('force-delete-faq');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-faq');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder-faq');
    }

    /**
     * @param User $user
     * @param Faq $faq
     * @return bool
     */
    public function replicate(User $user, Faq $faq): bool
    {
        return $user->can('replicate-faq');
    }

    /**
     * @param User $user
     * @param Faq $faq
     * @return bool
     */
    public function restore(User $user, Faq $faq): bool
    {
        return $user->can('restore-faq');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-faq');
    }

    /**
     * @param User $user
     * @param Faq $faq
     * @return bool
     */
    public function update(User $user, Faq $faq): bool
    {
        return $user->can('update-faq');
    }

    /**
     * @param User $user
     * @param Faq $faq
     * @return bool
     */
    public function view(User $user, Faq $faq): bool
    {
        return $user->can('view-faq');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-faq');
    }

    /**
     * @param User $user
     * @param Faq $faq
     * @return bool
     */
    public function viewFaqCategory(User $user, Faq $faq): bool
    {
        return $this->view($user, $faq);
    }

    /**
     * @param User $user
     * @param Faq $faq
     * @return bool
     */
    public function viewMultimedia(User $user, Faq $faq): bool
    {
        return $this->view($user, $faq);
    }
}
