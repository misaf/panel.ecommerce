<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Enums;

enum FaqCategoryPolicyEnum: string
{
    case Create = 'create-faq-category';
    case Delete = 'delete-faq-category';
    case DeleteAny = 'delete-any-faq-category';
    case ForceDelete = 'force-delete-faq-category';
    case ForceDeleteAny = 'force-delete-any-faq-category';
    case Reorder = 'reorder-faq-category';
    case Replicate = 'replicate-faq-category';
    case Restore = 'restore-faq-category';
    case RestoreAny = 'restore-any-faq-category';
    case Update = 'update-faq-category';
    case View = 'view-faq-category';
    case ViewAny = 'view-any-faq-category';
}
