<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Enums;

enum NewsletterPolicyEnum: string
{
    case Create = 'create-newsletter';
    case Delete = 'delete-newsletter';
    case DeleteAny = 'delete-any-newsletter';
    case ForceDelete = 'force-delete-newsletter';
    case ForceDeleteAny = 'force-delete-any-newsletter';
    case Replicate = 'replicate-newsletter';
    case Restore = 'restore-newsletter';
    case RestoreAny = 'restore-any-newsletter';
    case Send = 'send-newsletter';
    case Update = 'update-newsletter';
    case View = 'view-newsletter';
    case ViewAny = 'view-any-newsletter';
}
