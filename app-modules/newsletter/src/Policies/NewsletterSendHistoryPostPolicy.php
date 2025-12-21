<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Newsletter\Models\NewsletterSendHistoryPost;
use Misaf\User\Models\User;

final class NewsletterSendHistoryPostPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-newsletter-send-history-post');
    }

    /**
     * @param User $user
     * @param NewsletterSendHistoryPost $newsletterSendHistoryPost
     * @return bool
     */
    public function delete(User $user, NewsletterSendHistoryPost $newsletterSendHistoryPost): bool
    {
        return $user->can('delete-newsletter-send-history-post');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-newsletter-send-history-post');
    }

    /**
     * @param User $user
     * @param NewsletterSendHistoryPost $newsletterSendHistoryPost
     * @return bool
     */
    public function forceDelete(User $user, NewsletterSendHistoryPost $newsletterSendHistoryPost): bool
    {
        return $user->can('force-delete-newsletter-send-history-post');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-newsletter-send-history-post');
    }

    /**
     * @param User $user
     * @param NewsletterSendHistoryPost $newsletterSendHistoryPost
     * @return bool
     */
    public function replicate(User $user, NewsletterSendHistoryPost $newsletterSendHistoryPost): bool
    {
        return $user->can('replicate-newsletter-send-history-post');
    }

    /**
     * @param User $user
     * @param NewsletterSendHistoryPost $newsletterSendHistoryPost
     * @return bool
     */
    public function restore(User $user, NewsletterSendHistoryPost $newsletterSendHistoryPost): bool
    {
        return $user->can('restore-newsletter-send-history-post');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-newsletter-send-history-post');
    }

    /**
     * @param User $user
     * @param NewsletterSendHistoryPost $newsletterSendHistoryPost
     * @return bool
     */
    public function update(User $user, NewsletterSendHistoryPost $newsletterSendHistoryPost): bool
    {
        return $user->can('update-newsletter-send-history-post');
    }

    /**
     * @param User $user
     * @param NewsletterSendHistoryPost $newsletterSendHistoryPost
     * @return bool
     */
    public function view(User $user, NewsletterSendHistoryPost $newsletterSendHistoryPost): bool
    {
        return $user->can('view-newsletter-send-history-post');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-newsletter-send-history-post');
    }
}
