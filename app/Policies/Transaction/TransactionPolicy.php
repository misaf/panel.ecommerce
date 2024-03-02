<?php

declare(strict_types=1);

namespace App\Policies\Transaction;

use App\Models\Transaction\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class TransactionPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-transaction');
    }

    public function delete(User $user, Transaction $transaction): bool
    {
        return $user->can('delete-transaction');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-transaction');
    }

    public function forceDelete(User $user, Transaction $transaction): bool
    {
        return $user->can('force-delete-transaction');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-transaction');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder-transaction');
    }

    public function replicate(User $user, Transaction $transaction): bool
    {
        return $user->can('replicate-transaction');
    }

    public function restore(User $user, Transaction $transaction): bool
    {
        return $user->can('restore-transaction');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-transaction');
    }

    public function update(User $user, Transaction $transaction): bool
    {
        return $user->can('update-transaction');
    }

    public function view(User $user, Transaction $transaction): bool
    {
        return $user->can('view-transaction');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-transaction');
    }
}
