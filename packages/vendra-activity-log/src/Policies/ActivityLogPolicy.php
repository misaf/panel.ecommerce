<?php

declare(strict_types=1);

namespace Misaf\VendraActivityLog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraActivityLog\Enums\ActivityLogPolicyEnum;
use Misaf\VendraActivityLog\Models\ActivityLog;

final class ActivityLogPolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(ActivityLogPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, ActivityLog $activityLog): bool
    {
        return $user->can(ActivityLogPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(ActivityLogPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, ActivityLog $activityLog): bool
    {
        return $user->can(ActivityLogPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(ActivityLogPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function replicate(Authorizable $user, ActivityLog $activityLog): bool
    {
        return $user->can(ActivityLogPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, ActivityLog $activityLog): bool
    {
        return $user->can(ActivityLogPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(ActivityLogPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, ActivityLog $activityLog): bool
    {
        return $user->can(ActivityLogPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, ActivityLog $activityLog): bool
    {
        return $user->can(ActivityLogPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(ActivityLogPolicyEnum::VIEW_ANY->value);
    }
}
