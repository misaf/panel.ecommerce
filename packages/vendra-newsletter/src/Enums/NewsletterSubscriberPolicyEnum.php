<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Enums;

enum NewsletterSubscriberPolicyEnum: string
{
    case Create = 'create-newsletter-subscriber';
    case Delete = 'delete-newsletter-subscriber';
    case DeleteAny = 'delete-any-newsletter-subscriber';
    case ForceDelete = 'force-delete-newsletter-subscriber';
    case ForceDeleteAny = 'force-delete-any-newsletter-subscriber';
    case Restore = 'restore-newsletter-subscriber';
    case RestoreAny = 'restore-any-newsletter-subscriber';
    case Update = 'update-newsletter-subscriber';
    case View = 'view-newsletter-subscriber';
    case ViewAny = 'view-any-newsletter-subscriber';
}
