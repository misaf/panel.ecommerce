<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Enums;

enum CurrencyPolicyEnum: string
{
    case CREATE = 'create-currency';
    case DELETE = 'delete-currency';
    case DELETE_ANY = 'delete-any-currency';
    case FORCE_DELETE = 'force-delete-currency';
    case FORCE_DELETE_ANY = 'force-delete-any-currency';
    case REORDER = 'reorder-currency';
    case REPLICATE = 'replicate-currency';
    case RESTORE = 'restore-currency';
    case RESTORE_ANY = 'restore-any-currency';
    case UPDATE = 'update-currency';
    case VIEW = 'view-currency';
    case VIEW_ANY = 'view-any-currency';
}
