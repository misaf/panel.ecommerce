<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Enums;

enum LanguagePolicyEnum: string
{
    case CREATE = 'create-language';
    case DELETE = 'delete-language';
    case DELETE_ANY = 'delete-any-language';
    case FORCE_DELETE = 'force-delete-language';
    case FORCE_DELETE_ANY = 'force-delete-any-language';
    case REORDER = 'reorder-language';
    case REPLICATE = 'replicate-language';
    case RESTORE = 'restore-language';
    case RESTORE_ANY = 'restore-any-language';
    case UPDATE = 'update-language';
    case VIEW = 'view-language';
    case VIEW_ANY = 'view-any-language';
}
