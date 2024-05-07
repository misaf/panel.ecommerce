<?php

declare(strict_types=1);

namespace App\Policies\Product;

use App\Models\Product\ProductCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class ProductCategoryPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-product-category');
    }

    public function delete(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('delete-product-category');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-product-category');
    }

    public function forceDelete(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('force-delete-product-category');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-product-category');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder-product-category');
    }

    public function replicate(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('replicate-product-category');
    }

    public function restore(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('restore-product-category');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-product-category');
    }

    public function update(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('update-product-category');
    }

    public function view(?User $user, ProductCategory $productCategory): bool
    {
        return true;

        return $user->can('view-product-category');
    }

    public function viewAny(?User $user): bool
    {
        return true;

        return $user->can('view-any-product-category');
    }

    public function viewMultimedia(?User $user, ProductCategory $productCategory)
    {
        return $this->view($user, $productCategory);
    }

    public function viewProductPrices(?User $user, ProductCategory $productCategory)
    {
        return $this->view($user, $productCategory);
    }

    public function viewProducts(?User $user, ProductCategory $productCategory)
    {
        return $this->view($user, $productCategory);
    }
}
