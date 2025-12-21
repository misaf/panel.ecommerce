<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Newsletter\Models\NewsletterSendHistory;
use Misaf\User\Models\User;

final class NewsletterSendHistoryPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-newsletter-send-history');
    }

    /**
     * @param User $user
     * @param NewsletterSendHistory $newsletterSendHistory
     * @return bool
     */
    public function delete(User $user, NewsletterSendHistory $newsletterSendHistory): bool
    {
        return $user->can('delete-newsletter-send-history');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-newsletter-send-history');
    }

    /**
     * @param User $user
     * @param NewsletterSendHistory $newsletterSendHistory
     * @return bool
     */
    public function forceDelete(User $user, NewsletterSendHistory $newsletterSendHistory): bool
    {
        return $user->can('force-delete-newsletter-send-history');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-newsletter-send-history');
    }

    /**
     * @param User $user
     * @param NewsletterSendHistory $newsletterSendHistory
     * @return bool
     */
    public function replicate(User $user, NewsletterSendHistory $newsletterSendHistory): bool
    {
        return $user->can('replicate-newsletter-send-history');
    }

    /**
     * @param User $user
     * @param NewsletterSendHistory $newsletterSendHistory
     * @return bool
     */
    public function restore(User $user, NewsletterSendHistory $newsletterSendHistory): bool
    {
        return $user->can('restore-newsletter-send-history');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-newsletter-send-history');
    }

    /**
     * @param User $user
     * @param NewsletterSendHistory $newsletterSendHistory
     * @return bool
     */
    public function update(User $user, NewsletterSendHistory $newsletterSendHistory): bool
    {
        return $user->can('update-newsletter-send-history');
    }

    /**
     * @param User $user
     * @param NewsletterSendHistory $newsletterSendHistory
     * @return bool
     */
    public function view(User $user, NewsletterSendHistory $newsletterSendHistory): bool
    {
        return $user->can('view-newsletter-send-history');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-newsletter-send-history');
    }
}
