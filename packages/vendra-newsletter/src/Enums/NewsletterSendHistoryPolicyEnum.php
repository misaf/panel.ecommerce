<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Enums;

enum NewsletterSendHistoryPolicyEnum: string
{
    case CREATE = 'create-newsletter-send-history';
    case DELETE = 'delete-newsletter-send-history';
    case DELETE_ANY = 'delete-any-newsletter-send-history';
    case FORCE_DELETE = 'force-delete-newsletter-send-history';
    case FORCE_DELETE_ANY = 'force-delete-any-newsletter-send-history';
    case REPLICATE = 'replicate-newsletter-send-history';
    case RESTORE = 'restore-newsletter-send-history';
    case RESTORE_ANY = 'restore-any-newsletter-send-history';
    case UPDATE = 'update-newsletter-send-history';
    case VIEW = 'view-newsletter-send-history';
    case VIEW_ANY = 'view-any-newsletter-send-history';
}
