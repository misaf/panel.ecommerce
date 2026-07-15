<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraNewsletter\Enums\NewsletterSubscriberPolicyEnum;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class NewsletterSubscriberPolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(NewsletterSubscriberPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->can(NewsletterSubscriberPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSubscriberPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->can(NewsletterSubscriberPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSubscriberPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function replicate(Authorizable $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->can(NewsletterSubscriberPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->can(NewsletterSubscriberPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSubscriberPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->can(NewsletterSubscriberPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->can(NewsletterSubscriberPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSubscriberPolicyEnum::VIEW_ANY->value);
    }
}
