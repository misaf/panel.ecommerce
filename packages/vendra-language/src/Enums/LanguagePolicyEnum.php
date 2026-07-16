<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Enums;

enum LanguagePolicyEnum: string
{
    case Create = 'create-language';
    case Delete = 'delete-language';
    case DeleteAny = 'delete-any-language';
    case Reorder = 'reorder-language';
    case Update = 'update-language';
    case View = 'view-language';
    case ViewAny = 'view-any-language';
}
