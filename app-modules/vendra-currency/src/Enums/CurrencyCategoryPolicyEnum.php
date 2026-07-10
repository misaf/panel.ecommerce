<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Enums;

enum CurrencyCategoryPolicyEnum: string
{
    case CREATE = 'create-currency-category';
    case DELETE = 'delete-currency-category';
    case DELETE_ANY = 'delete-any-currency-category';
    case FORCE_DELETE = 'force-delete-currency-category';
    case FORCE_DELETE_ANY = 'force-delete-any-currency-category';
    case REORDER = 'reorder-currency-category';
    case REPLICATE = 'replicate-currency-category';
    case RESTORE = 'restore-currency-category';
    case RESTORE_ANY = 'restore-any-currency-category';
    case UPDATE = 'update-currency-category';
    case VIEW = 'view-currency-category';
    case VIEW_ANY = 'view-any-currency-category';
}
