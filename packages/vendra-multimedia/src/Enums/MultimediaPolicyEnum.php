<?php

declare(strict_types=1);

namespace Misaf\VendraMultimedia\Enums;

enum MultimediaPolicyEnum: string
{
    case Create = 'create-multimedia';
    case Delete = 'delete-multimedia';
    case DeleteAny = 'delete-any-multimedia';
    case ForceDelete = 'force-delete-multimedia';
    case ForceDeleteAny = 'force-delete-any-multimedia';
    case Replicate = 'replicate-multimedia';
    case Restore = 'restore-multimedia';
    case RestoreAny = 'restore-any-multimedia';
    case Update = 'update-multimedia';
    case View = 'view-multimedia';
    case ViewAny = 'view-any-multimedia';
}
