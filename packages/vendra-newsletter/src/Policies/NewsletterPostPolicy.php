<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraNewsletter\Enums\NewsletterPostPolicyEnum;
use Misaf\VendraNewsletter\Models\NewsletterPost;

final class NewsletterPostPolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(NewsletterPostPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, NewsletterPost $newsletterPost): bool
    {
        return $user->can(NewsletterPostPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(NewsletterPostPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, NewsletterPost $newsletterPost): bool
    {
        return $user->can(NewsletterPostPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(NewsletterPostPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function replicate(Authorizable $user, NewsletterPost $newsletterPost): bool
    {
        return $user->can(NewsletterPostPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, NewsletterPost $newsletterPost): bool
    {
        return $user->can(NewsletterPostPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(NewsletterPostPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, NewsletterPost $newsletterPost): bool
    {
        return $user->can(NewsletterPostPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, NewsletterPost $newsletterPost): bool
    {
        return $user->can(NewsletterPostPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(NewsletterPostPolicyEnum::VIEW_ANY->value);
    }
}
