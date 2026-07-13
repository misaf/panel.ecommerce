<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraNewsletter\Enums\NewsletterSendHistorySubscriberPolicyEnum;
use Misaf\VendraNewsletter\Models\NewsletterSendHistorySubscriber;

final class NewsletterSendHistorySubscriberPolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistorySubscriberPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, NewsletterSendHistorySubscriber $newsletterSendHistorySubscriber): bool
    {
        return $user->can(NewsletterSendHistorySubscriberPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistorySubscriberPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, NewsletterSendHistorySubscriber $newsletterSendHistorySubscriber): bool
    {
        return $user->can(NewsletterSendHistorySubscriberPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistorySubscriberPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function replicate(Authorizable $user, NewsletterSendHistorySubscriber $newsletterSendHistorySubscriber): bool
    {
        return $user->can(NewsletterSendHistorySubscriberPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, NewsletterSendHistorySubscriber $newsletterSendHistorySubscriber): bool
    {
        return $user->can(NewsletterSendHistorySubscriberPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistorySubscriberPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, NewsletterSendHistorySubscriber $newsletterSendHistorySubscriber): bool
    {
        return $user->can(NewsletterSendHistorySubscriberPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, NewsletterSendHistorySubscriber $newsletterSendHistorySubscriber): bool
    {
        return $user->can(NewsletterSendHistorySubscriberPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistorySubscriberPolicyEnum::VIEW_ANY->value);
    }
}
