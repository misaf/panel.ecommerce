<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Enums;

enum FaqCategoryPolicyEnum: string
{
    case CREATE = 'create-faq-category';
    case DELETE = 'delete-faq-category';
    case DELETE_ANY = 'delete-any-faq-category';
    case FORCE_DELETE = 'force-delete-faq-category';
    case FORCE_DELETE_ANY = 'force-delete-any-faq-category';
    case REORDER = 'reorder-faq-category';
    case REPLICATE = 'replicate-faq-category';
    case RESTORE = 'restore-faq-category';
    case RESTORE_ANY = 'restore-any-faq-category';
    case UPDATE = 'update-faq-category';
    case VIEW = 'view-faq-category';
    case VIEW_ANY = 'view-any-faq-category';
}
