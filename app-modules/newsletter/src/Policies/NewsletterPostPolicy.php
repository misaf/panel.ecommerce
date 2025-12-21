<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Newsletter\Models\NewsletterPost;
use Misaf\User\Models\User;

final class NewsletterPostPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-newsletter-post');
    }

    /**
     * @param User $user
     * @param NewsletterPost $newsletterPost
     * @return bool
     */
    public function delete(User $user, NewsletterPost $newsletterPost): bool
    {
        return $user->can('delete-newsletter-post');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-newsletter-post');
    }

    /**
     * @param User $user
     * @param NewsletterPost $newsletterPost
     * @return bool
     */
    public function forceDelete(User $user, NewsletterPost $newsletterPost): bool
    {
        return $user->can('force-delete-newsletter-post');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-newsletter-post');
    }

    /**
     * @param User $user
     * @param NewsletterPost $newsletterPost
     * @return bool
     */
    public function replicate(User $user, NewsletterPost $newsletterPost): bool
    {
        return $user->can('replicate-newsletter-post');
    }

    /**
     * @param User $user
     * @param NewsletterPost $newsletterPost
     * @return bool
     */
    public function restore(User $user, NewsletterPost $newsletterPost): bool
    {
        return $user->can('restore-newsletter-post');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-newsletter-post');
    }

    /**
     * @param User $user
     * @param NewsletterPost $newsletterPost
     * @return bool
     */
    public function update(User $user, NewsletterPost $newsletterPost): bool
    {
        return $user->can('update-newsletter-post');
    }

    /**
     * @param User $user
     * @param NewsletterPost $newsletterPost
     * @return bool
     */
    public function view(User $user, NewsletterPost $newsletterPost): bool
    {
        return $user->can('view-newsletter-post');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-newsletter-post');
    }
}
