<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Enums;

enum LanguageLinePolicyEnum: string
{
    case CREATE = 'create-language-line';
    case DELETE = 'delete-language-line';
    case DELETE_ANY = 'delete-any-language-line';
    case REPLICATE = 'replicate-language-line';
    case UPDATE = 'update-language-line';
    case VIEW = 'view-language-line';
    case VIEW_ANY = 'view-any-language-line';
}
