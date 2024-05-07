<?php

declare(strict_types=1);

namespace App\Policies\Blog;

use App\Models\Blog\BlogPost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class BlogPostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-blog-post');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BlogPost $blogPost): bool
    {
        return $user->can('delete-blog-post');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-blog-post');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BlogPost $blogPost): bool
    {
        return $user->can('force-delete-blog-post');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-blog-post');
    }

    /**
     * Determine whether the user can reorder the model.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder-blog-post');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, BlogPost $blogPost): bool
    {
        return $user->can('replicate-blog-post');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BlogPost $blogPost): bool
    {
        return $user->can('restore-blog-post');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-blog-post');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BlogPost $blogPost): bool
    {
        return $user->can('update-blog-post');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, BlogPost $blogPost): bool
    {
        return true;

        return $user->can('view-blog-post');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;

        return $user->can('view-any-blog-post');
    }

    /**
     * Determine whether the user can view the blog post category model.
     */
    public function viewBlogPostCategory(?User $user, BlogPost $blogPost)
    {
        return $this->view($user, $blogPost);
    }

    /**
     * Determine whether the user can view the multimedia model.
     */
    public function viewMultimedia(?User $user, BlogPost $blogPost)
    {
        return $this->view($user, $blogPost);
    }
}
