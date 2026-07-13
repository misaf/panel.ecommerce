<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Enums;

enum CountryPolicyEnum: string
{
    case CREATE = 'create-country';
    case DELETE = 'delete-country';
    case DELETE_ANY = 'delete-any-country';
    case FORCE_DELETE = 'force-delete-country';
    case FORCE_DELETE_ANY = 'force-delete-any-country';
    case REORDER = 'reorder-country';
    case REPLICATE = 'replicate-country';
    case RESTORE = 'restore-country';
    case RESTORE_ANY = 'restore-any-country';
    case UPDATE = 'update-country';
    case VIEW = 'view-country';
    case VIEW_ANY = 'view-any-country';
}
