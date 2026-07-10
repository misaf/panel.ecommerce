<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraNewsletter\Enums\NewsletterSendHistoryPostPolicyEnum;
use Misaf\VendraNewsletter\Models\NewsletterSendHistoryPost;

final class NewsletterSendHistoryPostPolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistoryPostPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, NewsletterSendHistoryPost $newsletterSendHistoryPost): bool
    {
        return $user->can(NewsletterSendHistoryPostPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistoryPostPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, NewsletterSendHistoryPost $newsletterSendHistoryPost): bool
    {
        return $user->can(NewsletterSendHistoryPostPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistoryPostPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function replicate(Authorizable $user, NewsletterSendHistoryPost $newsletterSendHistoryPost): bool
    {
        return $user->can(NewsletterSendHistoryPostPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, NewsletterSendHistoryPost $newsletterSendHistoryPost): bool
    {
        return $user->can(NewsletterSendHistoryPostPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistoryPostPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, NewsletterSendHistoryPost $newsletterSendHistoryPost): bool
    {
        return $user->can(NewsletterSendHistoryPostPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, NewsletterSendHistoryPost $newsletterSendHistoryPost): bool
    {
        return $user->can(NewsletterSendHistoryPostPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistoryPostPolicyEnum::VIEW_ANY->value);
    }
}
