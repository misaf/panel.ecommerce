<?php

declare(strict_types=1);

namespace Misaf\User\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Misaf\User\Models\User;

final class UserObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function deleted(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $user->userProfiles()->each(function ($userProfile): void {
                $userProfile->userProfileDocuments()->delete();
                $userProfile->userProfilePhones()->delete();

                $userProfile->delete();
            });
        });
    }
}
