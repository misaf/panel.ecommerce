<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Enums;

enum CustomPagePolicyEnum: string
{
    case CREATE = 'create-custom-page';
    case DELETE = 'delete-custom-page';
    case DELETE_ANY = 'delete-any-custom-page';
    case FORCE_DELETE = 'force-delete-custom-page';
    case FORCE_DELETE_ANY = 'force-delete-any-custom-page';
    case REORDER = 'reorder-custom-page';
    case REPLICATE = 'replicate-custom-page';
    case RESTORE = 'restore-custom-page';
    case RESTORE_ANY = 'restore-any-custom-page';
    case UPDATE = 'update-custom-page';
    case VIEW = 'view-custom-page';
    case VIEW_ANY = 'view-any-custom-page';
}
