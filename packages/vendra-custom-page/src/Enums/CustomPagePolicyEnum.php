<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Enums;

enum CustomPagePolicyEnum: string
{
    case Create = 'create-custom-page';
    case Delete = 'delete-custom-page';
    case DeleteAny = 'delete-any-custom-page';
    case ForceDelete = 'force-delete-custom-page';
    case ForceDeleteAny = 'force-delete-any-custom-page';
    case Reorder = 'reorder-custom-page';
    case Replicate = 'replicate-custom-page';
    case Restore = 'restore-custom-page';
    case RestoreAny = 'restore-any-custom-page';
    case Update = 'update-custom-page';
    case View = 'view-custom-page';
    case ViewAny = 'view-any-custom-page';
}
