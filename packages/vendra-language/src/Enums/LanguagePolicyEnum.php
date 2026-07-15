<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Enums;

enum LanguagePolicyEnum: string
{
    case CREATE = 'create-language';
    case DELETE = 'delete-language';
    case DELETE_ANY = 'delete-any-language';
    case REORDER = 'reorder-language';
    case UPDATE = 'update-language';
    case VIEW = 'view-language';
    case VIEW_ANY = 'view-any-language';
}
