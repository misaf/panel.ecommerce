<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Reseller;
use App\Models\ResellerUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use LogicException;

final class CreateResellerOwnerAction
{
    public function execute(
        Reseller $reseller,
        string $username,
        string $email,
        string $password,
        bool $emailVerified = true,
    ): ResellerUser {
        return DB::transaction(function () use ($reseller, $username, $email, $password, $emailVerified): ResellerUser {
            $lockedReseller = Reseller::query()
                ->whereKey($reseller->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedReseller->ownerUser()->exists()) {
                throw new LogicException("Reseller [{$lockedReseller->id}] already has an owner account.");
            }

            $owner = ResellerUser::query()->create([
                'reseller_id'       => $lockedReseller->getKey(),
                'username'          => $username,
                'email'             => $email,
                'email_verified_at' => $emailVerified ? Carbon::now() : null,
                'password'          => Hash::make($password),
            ]);

            $lockedReseller->update(['email' => $email]);

            return $owner;
        });
    }
}
