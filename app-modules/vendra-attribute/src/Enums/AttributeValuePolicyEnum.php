<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Enums;

enum AttributeValuePolicyEnum: string
{
    case CREATE = 'create-attribute-value';
    case DELETE = 'delete-attribute-value';
    case DELETE_ANY = 'delete-any-attribute-value';
    case FORCE_DELETE = 'force-delete-attribute-value';
    case FORCE_DELETE_ANY = 'force-delete-any-attribute-value';
    case REORDER = 'reorder-attribute-value';
    case REPLICATE = 'replicate-attribute-value';
    case RESTORE = 'restore-attribute-value';
    case RESTORE_ANY = 'restore-any-attribute-value';
    case UPDATE = 'update-attribute-value';
    case VIEW = 'view-attribute-value';
    case VIEW_ANY = 'view-any-attribute-value';
}
