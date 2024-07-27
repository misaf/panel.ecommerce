<?php

declare(strict_types=1);

namespace Termehsoft\User\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Termehsoft\User\Models\UserProfilePhone;

final class UserProfilePhoneObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    /**
     * Handle the UserProfilePhone "deleted" event.
     *
     * @param UserProfilePhone $userProfilePhone
     * @return void
     */
    public function deleted(UserProfilePhone $userProfilePhone): void
    {
        $this->clearCaches($userProfilePhone);
    }

    /**
     * Handle the UserProfilePhone "saved" event.
     *
     * @param UserProfilePhone $userProfilePhone
     * @return void
     */
    public function saved(UserProfilePhone $userProfilePhone): void
    {
        $this->clearCaches($userProfilePhone);
    }

    /**
     * Clear relevant caches.
     *
     * @param UserProfilePhone $userProfilePhone
     * @return void
     */
    private function clearCaches(UserProfilePhone $userProfilePhone): void
    {
        $this->forgetRowCountCache();
    }

    /**
     * Forget the user profile phone row count cache.
     * @return void
     */
    private function forgetRowCountCache(): void
    {
        Cache::forget('user-profile-phone-row-count');
    }
}
