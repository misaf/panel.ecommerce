<?php

declare(strict_types=1);

namespace App\Policies\Product;

use App\Models\Product\ProductCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class ProductCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-product-category');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('delete-product-category');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-product-category');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('force-delete-product-category');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-product-category');
    }

    /**
     * Determine whether the user can reorder the model.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder-product-category');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('replicate-product-category');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('restore-product-category');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-product-category');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('update-product-category');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, ProductCategory $productCategory): bool
    {
        return true;

        return $user->can('view-product-category');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;

        return $user->can('view-any-product-category');
    }

    /**
     * Determine whether the user can view the multimedia model.
     */
    public function viewMultimedia(?User $user, ProductCategory $productCategory)
    {
        return $this->view($user, $productCategory);
    }

    /**
     * Determine whether the user can view the product prices model.
     */
    public function viewProductPrices(?User $user, ProductCategory $productCategory)
    {
        return $this->view($user, $productCategory);
    }

    /**
     * Determine whether the user can view the products model.
     */
    public function viewProducts(?User $user, ProductCategory $productCategory)
    {
        return $this->view($user, $productCategory);
    }
}
