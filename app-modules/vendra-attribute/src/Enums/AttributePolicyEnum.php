<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Enums;

enum AttributePolicyEnum: string
{
    case CREATE = 'create-attribute';
    case DELETE = 'delete-attribute';
    case DELETE_ANY = 'delete-any-attribute';
    case FORCE_DELETE = 'force-delete-attribute';
    case FORCE_DELETE_ANY = 'force-delete-any-attribute';
    case REORDER = 'reorder-attribute';
    case REPLICATE = 'replicate-attribute';
    case RESTORE = 'restore-attribute';
    case RESTORE_ANY = 'restore-any-attribute';
    case UPDATE = 'update-attribute';
    case VIEW = 'view-attribute';
    case VIEW_ANY = 'view-any-attribute';
}
