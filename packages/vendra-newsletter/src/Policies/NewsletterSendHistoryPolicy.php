<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraNewsletter\Enums\NewsletterSendHistoryPolicyEnum;
use Misaf\VendraNewsletter\Models\NewsletterSendHistory;

final class NewsletterSendHistoryPolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistoryPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, NewsletterSendHistory $newsletterSendHistory): bool
    {
        return $user->can(NewsletterSendHistoryPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistoryPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, NewsletterSendHistory $newsletterSendHistory): bool
    {
        return $user->can(NewsletterSendHistoryPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistoryPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function replicate(Authorizable $user, NewsletterSendHistory $newsletterSendHistory): bool
    {
        return $user->can(NewsletterSendHistoryPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, NewsletterSendHistory $newsletterSendHistory): bool
    {
        return $user->can(NewsletterSendHistoryPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistoryPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, NewsletterSendHistory $newsletterSendHistory): bool
    {
        return $user->can(NewsletterSendHistoryPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, NewsletterSendHistory $newsletterSendHistory): bool
    {
        return $user->can(NewsletterSendHistoryPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(NewsletterSendHistoryPolicyEnum::VIEW_ANY->value);
    }
}
