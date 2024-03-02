<?php

declare(strict_types=1);

namespace App\Policies\Geographical;

use App\Models\Geographical\GeographicalZone;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class GeographicalZonePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-geographical-zone');
    }

    public function delete(User $user, GeographicalZone $geographicalZone): bool
    {
        return $user->can('delete-geographical-zone');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-geographical-zone');
    }

    public function forceDelete(User $user, GeographicalZone $geographicalZone): bool
    {
        return $user->can('force-delete-geographical-zone');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-geographical-zone');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder-geographical-zone');
    }

    public function replicate(User $user, GeographicalZone $geographicalZone): bool
    {
        return $user->can('replicate-geographical-zone');
    }

    public function restore(User $user, GeographicalZone $geographicalZone): bool
    {
        return $user->can('restore-geographical-zone');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-geographical-zone');
    }

    public function update(User $user, GeographicalZone $geographicalZone): bool
    {
        return $user->can('update-geographical-zone');
    }

    public function view(User $user, GeographicalZone $geographicalZone): bool
    {
        return $user->can('view-geographical-zone');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-geographical-zone');
    }
}
