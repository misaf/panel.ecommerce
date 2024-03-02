<?php

declare(strict_types=1);

namespace App\Policies\Geographical;

use App\Models\Geographical\GeographicalCountry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class GeographicalCountryPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-geographical-country');
    }

    public function delete(User $user, GeographicalCountry $geographicalCountry): bool
    {
        return $user->can('delete-geographical-country');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-geographical-country');
    }

    public function forceDelete(User $user, GeographicalCountry $geographicalCountry): bool
    {
        return $user->can('force-delete-geographical-country');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-geographical-country');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder-geographical-country');
    }

    public function replicate(User $user, GeographicalCountry $geographicalCountry): bool
    {
        return $user->can('replicate-geographical-country');
    }

    public function restore(User $user, GeographicalCountry $geographicalCountry): bool
    {
        return $user->can('restore-geographical-country');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-geographical-country');
    }

    public function update(User $user, GeographicalCountry $geographicalCountry): bool
    {
        return $user->can('update-geographical-country');
    }

    public function view(User $user, GeographicalCountry $geographicalCountry): bool
    {
        return $user->can('view-geographical-country');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-geographical-country');
    }
}
