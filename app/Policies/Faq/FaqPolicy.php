<?php

declare(strict_types=1);

namespace App\Policies\Faq;

use App\Models\Faq\Faq;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class FaqPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-faq');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Faq $faq): bool
    {
        return $user->can('delete-faq');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-faq');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Faq $faq): bool
    {
        return $user->can('force-delete-faq');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-faq');
    }

    /**
     * Determine whether the user can reorder the model.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder-faq');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Faq $faq): bool
    {
        return $user->can('replicate-faq');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Faq $faq): bool
    {
        return $user->can('restore-faq');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-faq');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Faq $faq): bool
    {
        return $user->can('update-faq');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Faq $faq): bool
    {
        return true;

        return $user->can('view-faq');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;

        return $user->can('view-any-faq');
    }

    /**
     * Determine whether the user can view the faq category model.
     */
    public function viewFaqCategory(?User $user, Faq $faq)
    {
        return $this->view($user, $faq);
    }

    /**
     * Determine whether the user can view the multimedia model.
     */
    public function viewMultimedia(?User $user, Faq $faq)
    {
        return $this->view($user, $faq);
    }
}
