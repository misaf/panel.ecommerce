<?php

declare(strict_types=1);

namespace App\Observers\User;

use App\Models\User\UserProfile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

final class UserProfileObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function created(UserProfile $userProfile): void {}

    public function deleted(UserProfile $userProfile): void
    {
        DB::transaction(function () use ($userProfile): void {
            $userProfile->userProfileDocuments()->delete();
            $userProfile->userProfilePhones()->delete();
        });
    }

    public function forceDeleted(UserProfile $userProfile): void {}

    public function restored(UserProfile $userProfile): void {}

    public function updated(UserProfile $userProfile): void {}
}
