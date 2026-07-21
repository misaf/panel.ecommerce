<?php

declare(strict_types=1);

namespace Misaf\VendraTagger\Enums;

enum TaggerPolicyEnum: string
{
    case Create = 'create-tagger';
    case Delete = 'delete-tagger';
    case DeleteAny = 'delete-any-tagger';
    case ForceDelete = 'force-delete-tagger';
    case ForceDeleteAny = 'force-delete-any-tagger';
    case Reorder = 'reorder-tagger';
    case Restore = 'restore-tagger';
    case RestoreAny = 'restore-any-tagger';
    case Update = 'update-tagger';
    case View = 'view-tagger';
    case ViewAny = 'view-any-tagger';
}
