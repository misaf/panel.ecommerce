<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Enums;

enum BlogPostCategoryPolicyEnum: string
{
    case Create = 'create-blog-post-category';
    case Delete = 'delete-blog-post-category';
    case DeleteAny = 'delete-any-blog-post-category';
    case ForceDelete = 'force-delete-blog-post-category';
    case ForceDeleteAny = 'force-delete-any-blog-post-category';
    case Reorder = 'reorder-blog-post-category';
    case Restore = 'restore-blog-post-category';
    case RestoreAny = 'restore-any-blog-post-category';
    case Update = 'update-blog-post-category';
    case View = 'view-blog-post-category';
    case ViewAny = 'view-any-blog-post-category';
}
