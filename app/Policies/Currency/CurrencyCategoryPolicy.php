<?php

declare(strict_types=1);

namespace App\Policies\Currency;

use App\Models\Currency\CurrencyCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class CurrencyCategoryPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-currency-category');
    }

    public function delete(User $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can('delete-currency-category');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-currency-category');
    }

    public function forceDelete(User $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can('force-delete-currency-category');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-currency-category');
    }

    public function replicate(User $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can('replicate-currency-category');
    }

    public function restore(User $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can('restore-currency-category');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-currency-category');
    }

    public function update(User $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can('update-currency-category');
    }

    public function view(User $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can('view-currency-category');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-currency-category');
    }
}
