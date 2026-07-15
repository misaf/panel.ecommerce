<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;
use Misaf\VendraUserProfile\Enums\UserProfilePolicyEnum;
use Misaf\VendraUserProfile\Models\UserProfile;

final class UserProfilePolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(UserProfilePolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, UserProfile $userProfile): bool
    {
        return $user->can(UserProfilePolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(UserProfilePolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, UserProfile $userProfile): bool
    {
        return $user->can(UserProfilePolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(UserProfilePolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function replicate(Authorizable $user, UserProfile $userProfile): bool
    {
        return $user->can(UserProfilePolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, UserProfile $userProfile): bool
    {
        return $user->can(UserProfilePolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(UserProfilePolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, UserProfile $userProfile): bool
    {
        return $user->can(UserProfilePolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, UserProfile $userProfile): bool
    {
        return $user->can(UserProfilePolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(UserProfilePolicyEnum::VIEW_ANY->value);
    }
}
