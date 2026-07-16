<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Enums;

enum AttributeValuePolicyEnum: string
{
    case Create = 'create-attribute-value';
    case Delete = 'delete-attribute-value';
    case DeleteAny = 'delete-any-attribute-value';
    case ForceDelete = 'force-delete-attribute-value';
    case ForceDeleteAny = 'force-delete-any-attribute-value';
    case Reorder = 'reorder-attribute-value';
    case Replicate = 'replicate-attribute-value';
    case Restore = 'restore-attribute-value';
    case RestoreAny = 'restore-any-attribute-value';
    case Update = 'update-attribute-value';
    case View = 'view-attribute-value';
    case ViewAny = 'view-any-attribute-value';
}
