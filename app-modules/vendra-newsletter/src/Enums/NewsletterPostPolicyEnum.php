<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Enums;

enum NewsletterPostPolicyEnum: string
{
    case CREATE = 'create-newsletter-post';
    case DELETE = 'delete-newsletter-post';
    case DELETE_ANY = 'delete-any-newsletter-post';
    case FORCE_DELETE = 'force-delete-newsletter-post';
    case FORCE_DELETE_ANY = 'force-delete-any-newsletter-post';
    case REPLICATE = 'replicate-newsletter-post';
    case RESTORE = 'restore-newsletter-post';
    case RESTORE_ANY = 'restore-any-newsletter-post';
    case UPDATE = 'update-newsletter-post';
    case VIEW = 'view-newsletter-post';
    case VIEW_ANY = 'view-any-newsletter-post';
}
