<?php

declare(strict_types=1);

namespace App\Policies\Product;

use App\Models\Product\ProductPrice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class ProductPricePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-product-price');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductPrice $productPrice): bool
    {
        return $user->can('delete-product-price');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-product-price');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProductPrice $productPrice): bool
    {
        return $user->can('force-delete-product-price');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-product-price');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, ProductPrice $productPrice): bool
    {
        return $user->can('replicate-product-price');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductPrice $productPrice): bool
    {
        return $user->can('restore-product-price');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-product-price');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductPrice $productPrice): bool
    {
        return $user->can('update-product-price');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, ProductPrice $productPrice): bool
    {
        return true;

        return $user->can('view-product-price');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;

        return $user->can('view-any-product-price');
    }

    /**
     * Determine whether the user can view the currency model.
     */
    public function viewCurrency(?User $user, ProductPrice $productPrice)
    {
        return $this->view($user, $productPrice);
    }

    /**
     * Determine whether the user can view the product model.
     */
    public function viewProduct(?User $user, ProductPrice $productPrice)
    {
        return $this->view($user, $productPrice);
    }
}
