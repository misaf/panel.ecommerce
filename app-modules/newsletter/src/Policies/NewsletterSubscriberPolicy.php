<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Newsletter\Models\NewsletterSubscriber;
use Misaf\User\Models\User;

final class NewsletterSubscriberPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-newsletter-subscriber');
    }

    /**
     * @param User $user
     * @param NewsletterSubscriber $newsletterSubscriber
     * @return bool
     */
    public function delete(User $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->can('delete-newsletter-subscriber');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-newsletter-subscriber');
    }

    /**
     * @param User $user
     * @param NewsletterSubscriber $newsletterSubscriber
     * @return bool
     */
    public function forceDelete(User $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->can('force-delete-newsletter-subscriber');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-newsletter-subscriber');
    }

    /**
     * @param User $user
     * @param NewsletterSubscriber $newsletterSubscriber
     * @return bool
     */
    public function replicate(User $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->can('replicate-newsletter-subscriber');
    }

    /**
     * @param User $user
     * @param NewsletterSubscriber $newsletterSubscriber
     * @return bool
     */
    public function restore(User $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->can('restore-newsletter-subscriber');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-newsletter-subscriber');
    }

    /**
     * @param User $user
     * @param NewsletterSubscriber $newsletterSubscriber
     * @return bool
     */
    public function update(User $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->can('update-newsletter-subscriber');
    }

    /**
     * @param User $user
     * @param NewsletterSubscriber $newsletterSubscriber
     * @return bool
     */
    public function view(User $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->can('view-newsletter-subscriber');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-newsletter-subscriber');
    }
}
