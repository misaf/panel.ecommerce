<?php

declare(strict_types=1);

namespace App\Policies\Geographical;

use App\Models\Geographical\GeographicalCity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class GeographicalCityPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-geographical-city');
    }

    public function delete(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('delete-geographical-city');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-geographical-city');
    }

    public function forceDelete(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('force-delete-geographical-city');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-geographical-city');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder-geographical-city');
    }

    public function replicate(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('replicate-geographical-city');
    }

    public function restore(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('restore-geographical-city');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-geographical-city');
    }

    public function update(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('update-geographical-city');
    }

    public function view(User $user, GeographicalCity $geographicalCity): bool
    {
        return $user->can('view-geographical-city');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-geographical-city');
    }
}
