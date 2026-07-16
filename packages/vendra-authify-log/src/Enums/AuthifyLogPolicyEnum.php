<?php

declare(strict_types=1);

namespace Misaf\VendraAuthifyLog\Enums;

enum AuthifyLogPolicyEnum: string
{
    case Create = 'create-authify-log';
    case Delete = 'delete-authify-log';
    case DeleteAny = 'delete-any-authify-log';
    case ForceDelete = 'force-delete-authify-log';
    case ForceDeleteAny = 'force-delete-any-authify-log';
    case Replicate = 'replicate-authify-log';
    case Restore = 'restore-authify-log';
    case RestoreAny = 'restore-any-authify-log';
    case Update = 'update-authify-log';
    case View = 'view-authify-log';
    case ViewAny = 'view-any-authify-log';
}
