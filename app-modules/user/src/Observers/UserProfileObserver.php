<?php

declare(strict_types=1);

namespace Termehsoft\User\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Termehsoft\User\Models\UserProfile;

final class UserProfileObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    /**
     * Handle the UserProfile "deleted" event.
     *
     * @param UserProfile $userProfile
     */
    public function deleted(UserProfile $userProfile): void
    {
        DB::transaction(function () use ($userProfile): void {
            $userProfile->userProfileDocuments()->delete();
            $userProfile->userProfilePhones()->delete();
        });

        $this->clearCaches($userProfile);
    }

    /**
     * Handle the UserProfile "saved" event.
     *
     * @param UserProfile $userProfile
     * @return void
     */
    public function saved(UserProfile $userProfile): void
    {
        $this->clearCaches($userProfile);
    }

    /**
     * Clear relevant caches.
     *
     * @param UserProfile $userProfile
     * @return void
     */
    private function clearCaches(UserProfile $userProfile): void
    {
        $this->forgetRowCountCache();
    }

    /**
     * Forget the user profile row count cache.
     * @return void
     */
    private function forgetRowCountCache(): void
    {
        Cache::forget('user-profile-row-count');
    }
}
