<?php

declare(strict_types=1);

namespace App\Filament\Account\Concerns;

use Filament\Facades\Filament;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraUser\Models\User;

trait InteractsWithCurrentAccount
{
    /**
     * The billing account of the currently authenticated owner, if any.
     */
    protected function currentAccount(): ?Account
    {
        $user = Filament::auth()->user();

        if ( ! $user instanceof User || null === $user->account_id) {
            return null;
        }

        return Account::query()->find($user->account_id);
    }
}
