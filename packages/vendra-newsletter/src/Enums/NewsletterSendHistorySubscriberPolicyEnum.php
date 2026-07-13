<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Enums;

enum NewsletterSendHistorySubscriberPolicyEnum: string
{
    case CREATE = 'create-newsletter-send-history-subscriber';
    case DELETE = 'delete-newsletter-send-history-subscriber';
    case DELETE_ANY = 'delete-any-newsletter-send-history-subscriber';
    case FORCE_DELETE = 'force-delete-newsletter-send-history-subscriber';
    case FORCE_DELETE_ANY = 'force-delete-any-newsletter-send-history-subscriber';
    case REPLICATE = 'replicate-newsletter-send-history-subscriber';
    case RESTORE = 'restore-newsletter-send-history-subscriber';
    case RESTORE_ANY = 'restore-any-newsletter-send-history-subscriber';
    case UPDATE = 'update-newsletter-send-history-subscriber';
    case VIEW = 'view-newsletter-send-history-subscriber';
    case VIEW_ANY = 'view-any-newsletter-send-history-subscriber';
}
