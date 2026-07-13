<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Enums;

enum CityPolicyEnum: string
{
    case CREATE = 'create-city';
    case DELETE = 'delete-city';
    case DELETE_ANY = 'delete-any-city';
    case FORCE_DELETE = 'force-delete-city';
    case FORCE_DELETE_ANY = 'force-delete-any-city';
    case REORDER = 'reorder-city';
    case REPLICATE = 'replicate-city';
    case RESTORE = 'restore-city';
    case RESTORE_ANY = 'restore-any-city';
    case UPDATE = 'update-city';
    case VIEW = 'view-city';
    case VIEW_ANY = 'view-any-city';
}
