<?php

declare(strict_types=1);

namespace Misaf\User\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Misaf\User\Models\UserProfile;

final class UserProfileObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function deleted(UserProfile $userProfile): void
    {
        DB::transaction(function () use ($userProfile): void {
            $userProfile->userProfileDocuments()->delete();
            $userProfile->userProfilePhones()->delete();
        });
    }
}
