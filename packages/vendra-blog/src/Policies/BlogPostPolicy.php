<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraBlog\Enums\BlogPostPolicyEnum;
use Misaf\VendraBlog\Models\BlogPost;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class BlogPostPolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(BlogPostPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, BlogPost $blogPost): bool
    {
        return $user->can(BlogPostPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(BlogPostPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, BlogPost $blogPost): bool
    {
        return $user->can(BlogPostPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(BlogPostPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function reorder(Authorizable $user): bool
    {
        return $user->can(BlogPostPolicyEnum::REORDER->value);
    }

    public function replicate(Authorizable $user, BlogPost $blogPost): bool
    {
        return $user->can(BlogPostPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, BlogPost $blogPost): bool
    {
        return $user->can(BlogPostPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(BlogPostPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, BlogPost $blogPost): bool
    {
        return $user->can(BlogPostPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, BlogPost $blogPost): bool
    {
        return $user->can(BlogPostPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(BlogPostPolicyEnum::VIEW_ANY->value);
    }
}
