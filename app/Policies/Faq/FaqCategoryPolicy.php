<?php

declare(strict_types=1);

namespace App\Policies\Faq;

use App\Models\Faq\FaqCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class FaqCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-faq-category');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FaqCategory $faqCategory): bool
    {
        return $user->can('delete-faq-category');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-faq-category');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FaqCategory $faqCategory): bool
    {
        return $user->can('force-delete-faq-category');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-faq-category');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, FaqCategory $faqCategory): bool
    {
        return $user->can('replicate-faq-category');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FaqCategory $faqCategory): bool
    {
        return $user->can('restore-faq-category');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-faq-category');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FaqCategory $faqCategory): bool
    {
        return $user->can('update-faq-category');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, FaqCategory $faqCategory): bool
    {
        return true;

        return $user->can('view-faq-category');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;

        return $user->can('view-any-faq-category');
    }

    /**
     * Determine whether the user can view the faqs model.
     */
    public function viewFaqs(?User $user, FaqCategory $faqCategory)
    {
        return $this->view($user, $faqCategory);
    }

    /**
     * Determine whether the user can view the multimedia model.
     */
    public function viewMultimedia(?User $user, FaqCategory $faqCategory)
    {
        return $this->view($user, $faqCategory);
    }
}
