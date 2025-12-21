<?php

declare(strict_types=1);

namespace Misaf\Tag\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Tag\Models\Tag;
use Misaf\User\Models\User;

final class TagPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-tag');
    }

    /**
     * @param User $user
     * @param Tag $tag
     * @return bool
     */
    public function delete(User $user, Tag $tag): bool
    {
        return $user->can('delete-tag');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-tag');
    }

    /**
     * @param User $user
     * @param Tag $tag
     * @return bool
     */
    public function forceDelete(User $user, Tag $tag): bool
    {
        return $user->can('force-delete-tag');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-tag');
    }

    /**
     * @param User $user
     * @param Tag $tag
     * @return bool
     */
    public function replicate(User $user, Tag $tag): bool
    {
        return $user->can('replicate-tag');
    }

    /**
     * @param User $user
     * @param Tag $tag
     * @return bool
     */
    public function restore(User $user, Tag $tag): bool
    {
        return $user->can('restore-tag');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-tag');
    }

    /**
     * @param User $user
     * @param Tag $tag
     * @return bool
     */
    public function update(User $user, Tag $tag): bool
    {
        return $user->can('update-tag');
    }

    /**
     * @param User $user
     * @param Tag $tag
     * @return bool
     */
    public function view(User $user, Tag $tag): bool
    {
        return $user->can('view-tag');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-tag');
    }
}
