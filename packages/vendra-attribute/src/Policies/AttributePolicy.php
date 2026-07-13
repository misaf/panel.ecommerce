<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraAttribute\Enums\AttributePolicyEnum;
use Misaf\VendraAttribute\Models\Attribute;
use Misaf\VendraSupport\Support\SandboxMode;

final class AttributePolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $this->allowed($user, AttributePolicyEnum::CREATE);
    }

    public function delete(Authorizable $user, Attribute $attribute): bool
    {
        return $this->allowed($user, AttributePolicyEnum::DELETE);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $this->allowed($user, AttributePolicyEnum::DELETE_ANY);
    }

    public function forceDelete(Authorizable $user, Attribute $attribute): bool
    {
        return $this->allowed($user, AttributePolicyEnum::FORCE_DELETE);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $this->allowed($user, AttributePolicyEnum::FORCE_DELETE_ANY);
    }

    public function reorder(Authorizable $user): bool
    {
        return $this->allowed($user, AttributePolicyEnum::REORDER);
    }

    public function replicate(Authorizable $user, Attribute $attribute): bool
    {
        return $this->allowed($user, AttributePolicyEnum::REPLICATE);
    }

    public function restore(Authorizable $user, Attribute $attribute): bool
    {
        return $this->allowed($user, AttributePolicyEnum::RESTORE);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $this->allowed($user, AttributePolicyEnum::RESTORE_ANY);
    }

    public function update(Authorizable $user, Attribute $attribute): bool
    {
        return $this->allowed($user, AttributePolicyEnum::UPDATE);
    }

    public function view(Authorizable $user, Attribute $attribute): bool
    {
        return $this->allowed($user, AttributePolicyEnum::VIEW);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $this->allowed($user, AttributePolicyEnum::VIEW_ANY);
    }

    private function allowed(Authorizable $user, AttributePolicyEnum $permission): bool
    {
        return SandboxMode::enabled() || $user->can($permission->value);
    }
}
