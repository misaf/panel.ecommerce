<?php

declare(strict_types=1);

namespace Misaf\VendraMultimedia\Enums;

enum MultimediaPolicyEnum: string
{
    case CREATE = 'create-multimedia';
    case DELETE = 'delete-multimedia';
    case DELETE_ANY = 'delete-any-multimedia';
    case FORCE_DELETE = 'force-delete-multimedia';
    case FORCE_DELETE_ANY = 'force-delete-any-multimedia';
    case REPLICATE = 'replicate-multimedia';
    case RESTORE = 'restore-multimedia';
    case RESTORE_ANY = 'restore-any-multimedia';
    case UPDATE = 'update-multimedia';
    case VIEW = 'view-multimedia';
    case VIEW_ANY = 'view-any-multimedia';
}
