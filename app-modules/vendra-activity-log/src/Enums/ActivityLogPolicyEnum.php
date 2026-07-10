<?php

declare(strict_types=1);

namespace Misaf\VendraActivityLog\Enums;

enum ActivityLogPolicyEnum: string
{
    case CREATE = 'create-activity-log';
    case DELETE = 'delete-activity-log';
    case DELETE_ANY = 'delete-any-activity-log';
    case FORCE_DELETE = 'force-delete-activity-log';
    case FORCE_DELETE_ANY = 'force-delete-any-activity-log';
    case REPLICATE = 'replicate-activity-log';
    case RESTORE = 'restore-activity-log';
    case RESTORE_ANY = 'restore-any-activity-log';
    case UPDATE = 'update-activity-log';
    case VIEW = 'view-activity-log';
    case VIEW_ANY = 'view-any-activity-log';
}
