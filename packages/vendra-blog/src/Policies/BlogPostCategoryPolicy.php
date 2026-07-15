<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraBlog\Enums\BlogPostCategoryPolicyEnum;
use Misaf\VendraBlog\Models\BlogPostCategory;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class BlogPostCategoryPolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(BlogPostCategoryPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, BlogPostCategory $blogPostCategory): bool
    {
        return $user->can(BlogPostCategoryPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(BlogPostCategoryPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, BlogPostCategory $blogPostCategory): bool
    {
        return $user->can(BlogPostCategoryPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(BlogPostCategoryPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function reorder(Authorizable $user): bool
    {
        return $user->can(BlogPostCategoryPolicyEnum::REORDER->value);
    }

    public function replicate(Authorizable $user, BlogPostCategory $blogPostCategory): bool
    {
        return $user->can(BlogPostCategoryPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, BlogPostCategory $blogPostCategory): bool
    {
        return $user->can(BlogPostCategoryPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(BlogPostCategoryPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, BlogPostCategory $blogPostCategory): bool
    {
        return $user->can(BlogPostCategoryPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, BlogPostCategory $blogPostCategory): bool
    {
        return $user->can(BlogPostCategoryPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(BlogPostCategoryPolicyEnum::VIEW_ANY->value);
    }
}
