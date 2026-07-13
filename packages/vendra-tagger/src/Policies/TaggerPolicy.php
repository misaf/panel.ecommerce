<?php

declare(strict_types=1);

namespace Misaf\VendraTagger\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraTagger\Enums\TaggerPolicyEnum;
use Misaf\VendraTagger\Models\Tagger;

final class TaggerPolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(TaggerPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, Tagger $tagger): bool
    {
        return $user->can(TaggerPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(TaggerPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, Tagger $tagger): bool
    {
        return $user->can(TaggerPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(TaggerPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function reorder(Authorizable $user): bool
    {
        return $user->can(TaggerPolicyEnum::REORDER->value);
    }

    public function replicate(Authorizable $user, Tagger $tagger): bool
    {
        return $user->can(TaggerPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, Tagger $tagger): bool
    {
        return $user->can(TaggerPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(TaggerPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, Tagger $tagger): bool
    {
        return $user->can(TaggerPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, Tagger $tagger): bool
    {
        return $user->can(TaggerPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(TaggerPolicyEnum::VIEW_ANY->value);
    }
}
