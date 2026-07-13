<?php

declare(strict_types=1);

namespace Misaf\VendraMultimedia\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraMultimedia\Enums\MultimediaPolicyEnum;
use Misaf\VendraMultimedia\Models\Multimedia;

final class MultimediaPolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(MultimediaPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, Multimedia $multimedia): bool
    {
        return $user->can(MultimediaPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(MultimediaPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, Multimedia $multimedia): bool
    {
        return $user->can(MultimediaPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(MultimediaPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function replicate(Authorizable $user, Multimedia $multimedia): bool
    {
        return $user->can(MultimediaPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, Multimedia $multimedia): bool
    {
        return $user->can(MultimediaPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(MultimediaPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, Multimedia $multimedia): bool
    {
        return $user->can(MultimediaPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, Multimedia $multimedia): bool
    {
        return $user->can(MultimediaPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(MultimediaPolicyEnum::VIEW_ANY->value);
    }
}
