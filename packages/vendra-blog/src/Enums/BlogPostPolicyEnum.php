<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Enums;

enum BlogPostPolicyEnum: string
{
    case CREATE = 'create-blog-post';
    case DELETE = 'delete-blog-post';
    case DELETE_ANY = 'delete-any-blog-post';
    case FORCE_DELETE = 'force-delete-blog-post';
    case FORCE_DELETE_ANY = 'force-delete-any-blog-post';
    case REORDER = 'reorder-blog-post';
    case REPLICATE = 'replicate-blog-post';
    case RESTORE = 'restore-blog-post';
    case RESTORE_ANY = 'restore-any-blog-post';
    case UPDATE = 'update-blog-post';
    case VIEW = 'view-blog-post';
    case VIEW_ANY = 'view-any-blog-post';
}
