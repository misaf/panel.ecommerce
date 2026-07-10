<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Enums;

enum NewsletterSubscriberPolicyEnum: string
{
    case CREATE = 'create-newsletter-subscriber';
    case DELETE = 'delete-newsletter-subscriber';
    case DELETE_ANY = 'delete-any-newsletter-subscriber';
    case FORCE_DELETE = 'force-delete-newsletter-subscriber';
    case FORCE_DELETE_ANY = 'force-delete-any-newsletter-subscriber';
    case REPLICATE = 'replicate-newsletter-subscriber';
    case RESTORE = 'restore-newsletter-subscriber';
    case RESTORE_ANY = 'restore-any-newsletter-subscriber';
    case UPDATE = 'update-newsletter-subscriber';
    case VIEW = 'view-newsletter-subscriber';
    case VIEW_ANY = 'view-any-newsletter-subscriber';
}
