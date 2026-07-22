<?php

declare(strict_types=1);

namespace App\Filament\Reseller\Concerns;

use App\Models\Reseller;
use Filament\Facades\Filament;
use Misaf\VendraUser\Models\User;

trait InteractsWithCurrentReseller
{
    /**
     * The billing reseller of the currently authenticated owner, if any.
     */
    protected function currentReseller(): ?Reseller
    {
        $user = Filament::auth()->user();

        if ( ! $user instanceof User || null === $user->reseller_id) {
            return null;
        }

        return Reseller::query()->find($user->reseller_id);
    }
}
