<?php

declare(strict_types=1);

namespace Misaf\VendraDocument\Enums;

enum DocumentPolicyEnum: string
{
    case Create = 'create-document';
    case Delete = 'delete-document';
    case DeleteAny = 'delete-any-document';
    case ForceDelete = 'force-delete-document';
    case ForceDeleteAny = 'force-delete-any-document';
    case Restore = 'restore-document';
    case RestoreAny = 'restore-any-document';
    case Update = 'update-document';
    case View = 'view-document';
    case ViewAny = 'view-any-document';
}
