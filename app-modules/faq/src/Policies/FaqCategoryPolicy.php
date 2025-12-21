<?php

declare(strict_types=1);

namespace Misaf\Faq\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Faq\Models\FaqCategory;
use Misaf\User\Models\User;

final class FaqCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-faq-category');
    }

    /**
     * @param User $user
     * @param FaqCategory $faqCategory
     * @return bool
     */
    public function delete(User $user, FaqCategory $faqCategory): bool
    {
        return $user->can('delete-faq-category');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-faq-category');
    }

    /**
     * @param User $user
     * @param FaqCategory $faqCategory
     * @return bool
     */
    public function forceDelete(User $user, FaqCategory $faqCategory): bool
    {
        return $user->can('force-delete-faq-category');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-faq-category');
    }

    /**
     * @param User $user
     * @param FaqCategory $faqCategory
     * @return bool
     */
    public function replicate(User $user, FaqCategory $faqCategory): bool
    {
        return $user->can('replicate-faq-category');
    }

    /**
     * @param User $user
     * @param FaqCategory $faqCategory
     * @return bool
     */
    public function restore(User $user, FaqCategory $faqCategory): bool
    {
        return $user->can('restore-faq-category');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-faq-category');
    }

    /**
     * @param User $user
     * @param FaqCategory $faqCategory
     * @return bool
     */
    public function update(User $user, FaqCategory $faqCategory): bool
    {
        return $user->can('update-faq-category');
    }

    /**
     * @param User $user
     * @param FaqCategory $faqCategory
     * @return bool
     */
    public function view(User $user, FaqCategory $faqCategory): bool
    {
        return $user->can('view-faq-category');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-faq-category');
    }

    /**
     * @param User $user
     * @param FaqCategory $faqCategory
     * @return bool
     */
    public function viewFaqs(User $user, FaqCategory $faqCategory): bool
    {
        return $this->view($user, $faqCategory);
    }

    /**
     * @param User $user
     * @param FaqCategory $faqCategory
     * @return bool
     */
    public function viewMultimedia(User $user, FaqCategory $faqCategory): bool
    {
        return $this->view($user, $faqCategory);
    }
}
