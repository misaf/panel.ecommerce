<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Enums;

enum BlogPostCategoryPolicyEnum: string
{
    case CREATE = 'create-blog-post-category';
    case DELETE = 'delete-blog-post-category';
    case DELETE_ANY = 'delete-any-blog-post-category';
    case FORCE_DELETE = 'force-delete-blog-post-category';
    case FORCE_DELETE_ANY = 'force-delete-any-blog-post-category';
    case REORDER = 'reorder-blog-post-category';
    case REPLICATE = 'replicate-blog-post-category';
    case RESTORE = 'restore-blog-post-category';
    case RESTORE_ANY = 'restore-any-blog-post-category';
    case UPDATE = 'update-blog-post-category';
    case VIEW = 'view-blog-post-category';
    case VIEW_ANY = 'view-any-blog-post-category';
}
