<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Enums;

enum CustomPageCategoryPolicyEnum: string
{
    case CREATE = 'create-custom-page-category';
    case DELETE = 'delete-custom-page-category';
    case DELETE_ANY = 'delete-any-custom-page-category';
    case FORCE_DELETE = 'force-delete-custom-page-category';
    case FORCE_DELETE_ANY = 'force-delete-any-custom-page-category';
    case REORDER = 'reorder-custom-page-category';
    case REPLICATE = 'replicate-custom-page-category';
    case RESTORE = 'restore-custom-page-category';
    case RESTORE_ANY = 'restore-any-custom-page-category';
    case UPDATE = 'update-custom-page-category';
    case VIEW = 'view-custom-page-category';
    case VIEW_ANY = 'view-any-custom-page-category';
}
