<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Enums;

enum NewsletterSendHistoryPostPolicyEnum: string
{
    case CREATE = 'create-newsletter-send-history-post';
    case DELETE = 'delete-newsletter-send-history-post';
    case DELETE_ANY = 'delete-any-newsletter-send-history-post';
    case FORCE_DELETE = 'force-delete-newsletter-send-history-post';
    case FORCE_DELETE_ANY = 'force-delete-any-newsletter-send-history-post';
    case REPLICATE = 'replicate-newsletter-send-history-post';
    case RESTORE = 'restore-newsletter-send-history-post';
    case RESTORE_ANY = 'restore-any-newsletter-send-history-post';
    case UPDATE = 'update-newsletter-send-history-post';
    case VIEW = 'view-newsletter-send-history-post';
    case VIEW_ANY = 'view-any-newsletter-send-history-post';
}
