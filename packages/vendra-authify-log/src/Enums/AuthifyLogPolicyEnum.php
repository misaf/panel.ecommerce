<?php

declare(strict_types=1);

namespace Misaf\VendraAuthifyLog\Enums;

enum AuthifyLogPolicyEnum: string
{
    case CREATE = 'create-authify-log';
    case DELETE = 'delete-authify-log';
    case DELETE_ANY = 'delete-any-authify-log';
    case FORCE_DELETE = 'force-delete-authify-log';
    case FORCE_DELETE_ANY = 'force-delete-any-authify-log';
    case REPLICATE = 'replicate-authify-log';
    case RESTORE = 'restore-authify-log';
    case RESTORE_ANY = 'restore-any-authify-log';
    case UPDATE = 'update-authify-log';
    case VIEW = 'view-authify-log';
    case VIEW_ANY = 'view-any-authify-log';
}
