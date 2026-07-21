<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Enums;

enum BlogPostPolicyEnum: string
{
    case Create = 'create-blog-post';
    case Delete = 'delete-blog-post';
    case DeleteAny = 'delete-any-blog-post';
    case ForceDelete = 'force-delete-blog-post';
    case ForceDeleteAny = 'force-delete-any-blog-post';
    case Reorder = 'reorder-blog-post';
    case Restore = 'restore-blog-post';
    case RestoreAny = 'restore-any-blog-post';
    case Update = 'update-blog-post';
    case View = 'view-blog-post';
    case ViewAny = 'view-any-blog-post';
}
