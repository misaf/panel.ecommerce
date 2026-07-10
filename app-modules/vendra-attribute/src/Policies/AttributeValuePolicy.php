<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraAttribute\Enums\AttributeValuePolicyEnum;
use Misaf\VendraAttribute\Models\AttributeValue;
use Misaf\VendraSupport\Support\SandboxMode;

final class AttributeValuePolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $this->allowed($user, AttributeValuePolicyEnum::CREATE);
    }

    public function delete(Authorizable $user, AttributeValue $attributeValue): bool
    {
        return $this->allowed($user, AttributeValuePolicyEnum::DELETE);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $this->allowed($user, AttributeValuePolicyEnum::DELETE_ANY);
    }

    public function forceDelete(Authorizable $user, AttributeValue $attributeValue): bool
    {
        return $this->allowed($user, AttributeValuePolicyEnum::FORCE_DELETE);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $this->allowed($user, AttributeValuePolicyEnum::FORCE_DELETE_ANY);
    }

    public function reorder(Authorizable $user): bool
    {
        return $this->allowed($user, AttributeValuePolicyEnum::REORDER);
    }

    public function replicate(Authorizable $user, AttributeValue $attributeValue): bool
    {
        return $this->allowed($user, AttributeValuePolicyEnum::REPLICATE);
    }

    public function restore(Authorizable $user, AttributeValue $attributeValue): bool
    {
        return $this->allowed($user, AttributeValuePolicyEnum::RESTORE);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $this->allowed($user, AttributeValuePolicyEnum::RESTORE_ANY);
    }

    public function update(Authorizable $user, AttributeValue $attributeValue): bool
    {
        return $this->allowed($user, AttributeValuePolicyEnum::UPDATE);
    }

    public function view(Authorizable $user, AttributeValue $attributeValue): bool
    {
        return $this->allowed($user, AttributeValuePolicyEnum::VIEW);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $this->allowed($user, AttributeValuePolicyEnum::VIEW_ANY);
    }

    private function allowed(Authorizable $user, AttributeValuePolicyEnum $permission): bool
    {
        return SandboxMode::enabled() || $user->can($permission->value);
    }
}
