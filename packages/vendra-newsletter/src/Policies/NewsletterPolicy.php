<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraNewsletter\Enums\NewsletterPolicyEnum;
use Misaf\VendraNewsletter\Enums\NewsletterStatusEnum;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class NewsletterPolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(NewsletterPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, Newsletter $newsletter): bool
    {
        return $user->can(NewsletterPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(NewsletterPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, Newsletter $newsletter): bool
    {
        return $user->can(NewsletterPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(NewsletterPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function replicate(Authorizable $user, Newsletter $newsletter): bool
    {
        return $user->can(NewsletterPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, Newsletter $newsletter): bool
    {
        return $user->can(NewsletterPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(NewsletterPolicyEnum::RESTORE_ANY->value);
    }

    public function send(Authorizable $user, Newsletter $newsletter): bool
    {
        return NewsletterStatusEnum::Sent !== $newsletter->status
            && $user->can(NewsletterPolicyEnum::SEND->value);
    }

    public function update(Authorizable $user, Newsletter $newsletter): bool
    {
        return NewsletterStatusEnum::Sent !== $newsletter->status
            && $user->can(NewsletterPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, Newsletter $newsletter): bool
    {
        return $user->can(NewsletterPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(NewsletterPolicyEnum::VIEW_ANY->value);
    }
}
