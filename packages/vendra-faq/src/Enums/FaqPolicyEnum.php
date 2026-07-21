<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Enums;

enum FaqPolicyEnum: string
{
    case Create = 'create-faq';
    case Delete = 'delete-faq';
    case DeleteAny = 'delete-any-faq';
    case ForceDelete = 'force-delete-faq';
    case ForceDeleteAny = 'force-delete-any-faq';
    case Reorder = 'reorder-faq';
    case Restore = 'restore-faq';
    case RestoreAny = 'restore-any-faq';
    case Update = 'update-faq';
    case View = 'view-faq';
    case ViewAny = 'view-any-faq';
}
