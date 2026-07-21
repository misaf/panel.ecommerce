<?php

declare(strict_types=1);

namespace Misaf\VendraPhone\Enums;

enum PhoneNumberPolicyEnum: string
{
    case Create = 'create-phone-number';
    case Delete = 'delete-phone-number';
    case DeleteAny = 'delete-any-phone-number';
    case ForceDelete = 'force-delete-phone-number';
    case ForceDeleteAny = 'force-delete-any-phone-number';
    case Restore = 'restore-phone-number';
    case RestoreAny = 'restore-any-phone-number';
    case Update = 'update-phone-number';
    case View = 'view-phone-number';
    case ViewAny = 'view-any-phone-number';
}
