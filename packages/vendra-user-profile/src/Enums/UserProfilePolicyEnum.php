<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Enums;

enum UserProfilePolicyEnum: string
{
    case Create = 'create-user-profile';
    case Delete = 'delete-user-profile';
    case DeleteAny = 'delete-any-user-profile';
    case ForceDelete = 'force-delete-user-profile';
    case ForceDeleteAny = 'force-delete-any-user-profile';
    case Replicate = 'replicate-user-profile';
    case Restore = 'restore-user-profile';
    case RestoreAny = 'restore-any-user-profile';
    case Update = 'update-user-profile';
    case View = 'view-user-profile';
    case ViewAny = 'view-any-user-profile';
}
