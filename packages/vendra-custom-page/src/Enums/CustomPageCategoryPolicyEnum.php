<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Enums;

enum CustomPageCategoryPolicyEnum: string
{
    case Create = 'create-custom-page-category';
    case Delete = 'delete-custom-page-category';
    case DeleteAny = 'delete-any-custom-page-category';
    case ForceDelete = 'force-delete-custom-page-category';
    case ForceDeleteAny = 'force-delete-any-custom-page-category';
    case Reorder = 'reorder-custom-page-category';
    case Restore = 'restore-custom-page-category';
    case RestoreAny = 'restore-any-custom-page-category';
    case Update = 'update-custom-page-category';
    case View = 'view-custom-page-category';
    case ViewAny = 'view-any-custom-page-category';
}
