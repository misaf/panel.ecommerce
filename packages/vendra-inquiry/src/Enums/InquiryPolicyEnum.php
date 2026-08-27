<?php

declare(strict_types=1);

namespace Misaf\VendraInquiry\Enums;

enum InquiryPolicyEnum: string
{
    case Delete = 'delete-inquiry';
    case DeleteAny = 'delete-any-inquiry';
    case ForceDelete = 'force-delete-inquiry';
    case ForceDeleteAny = 'force-delete-any-inquiry';
    case Restore = 'restore-inquiry';
    case RestoreAny = 'restore-any-inquiry';
    case Update = 'update-inquiry';
    case View = 'view-inquiry';
    case ViewAny = 'view-any-inquiry';
}
