<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Enums;

enum FaqPolicyEnum: string
{
    case CREATE = 'create-faq';
    case DELETE = 'delete-faq';
    case DELETE_ANY = 'delete-any-faq';
    case FORCE_DELETE = 'force-delete-faq';
    case FORCE_DELETE_ANY = 'force-delete-any-faq';
    case REORDER = 'reorder-faq';
    case REPLICATE = 'replicate-faq';
    case RESTORE = 'restore-faq';
    case RESTORE_ANY = 'restore-any-faq';
    case UPDATE = 'update-faq';
    case VIEW = 'view-faq';
    case VIEW_ANY = 'view-any-faq';
}
