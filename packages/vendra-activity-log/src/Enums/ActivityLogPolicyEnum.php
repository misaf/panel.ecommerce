<?php

declare(strict_types=1);

namespace Misaf\VendraActivityLog\Enums;

enum ActivityLogPolicyEnum: string
{
    case Create = 'create-activity-log';
    case Delete = 'delete-activity-log';
    case DeleteAny = 'delete-any-activity-log';
    case ForceDelete = 'force-delete-activity-log';
    case ForceDeleteAny = 'force-delete-any-activity-log';
    case Restore = 'restore-activity-log';
    case RestoreAny = 'restore-any-activity-log';
    case Update = 'update-activity-log';
    case View = 'view-activity-log';
    case ViewAny = 'view-any-activity-log';
}
