<?php

declare(strict_types=1);

namespace Misaf\VendraTagger\Enums;

enum TaggerPolicyEnum: string
{
    case CREATE = 'create-tagger';
    case DELETE = 'delete-tagger';
    case DELETE_ANY = 'delete-any-tagger';
    case FORCE_DELETE = 'force-delete-tagger';
    case FORCE_DELETE_ANY = 'force-delete-any-tagger';
    case REORDER = 'reorder-tagger';
    case REPLICATE = 'replicate-tagger';
    case RESTORE = 'restore-tagger';
    case RESTORE_ANY = 'restore-any-tagger';
    case UPDATE = 'update-tagger';
    case VIEW = 'view-tagger';
    case VIEW_ANY = 'view-any-tagger';
}
