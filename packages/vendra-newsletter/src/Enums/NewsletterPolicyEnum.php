<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Enums;

enum NewsletterPolicyEnum: string
{
    case CREATE = 'create-newsletter';
    case DELETE = 'delete-newsletter';
    case DELETE_ANY = 'delete-any-newsletter';
    case FORCE_DELETE = 'force-delete-newsletter';
    case FORCE_DELETE_ANY = 'force-delete-any-newsletter';
    case REPLICATE = 'replicate-newsletter';
    case RESTORE = 'restore-newsletter';
    case RESTORE_ANY = 'restore-any-newsletter';
    case SEND = 'send-newsletter';
    case UPDATE = 'update-newsletter';
    case VIEW = 'view-newsletter';
    case VIEW_ANY = 'view-any-newsletter';
}
