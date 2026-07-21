<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Enums;

enum LanguageLinePolicyEnum: string
{
    case Create = 'create-language-line';
    case Delete = 'delete-language-line';
    case DeleteAny = 'delete-any-language-line';
    case Update = 'update-language-line';
    case View = 'view-language-line';
    case ViewAny = 'view-any-language-line';
}
