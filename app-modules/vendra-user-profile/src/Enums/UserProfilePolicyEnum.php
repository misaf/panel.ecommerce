<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Enums;

enum UserProfilePolicyEnum: string
{
    case CREATE = 'create-user-profile';
    case DELETE = 'delete-user-profile';
    case DELETE_ANY = 'delete-any-user-profile';
    case FORCE_DELETE = 'force-delete-user-profile';
    case FORCE_DELETE_ANY = 'force-delete-any-user-profile';
    case REPLICATE = 'replicate-user-profile';
    case RESTORE = 'restore-user-profile';
    case RESTORE_ANY = 'restore-any-user-profile';
    case UPDATE = 'update-user-profile';
    case VIEW = 'view-user-profile';
    case VIEW_ANY = 'view-any-user-profile';
}
