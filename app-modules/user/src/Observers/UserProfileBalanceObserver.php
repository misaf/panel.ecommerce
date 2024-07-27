<?php

declare(strict_types=1);

namespace Termehsoft\User\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Termehsoft\User\Models\UserProfileBalance;

final class UserProfileBalanceObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    /**
     * Handle the UserProfileBalance "deleted" event.
     *
     * @param UserProfileBalance $userProfileBalance
     * @return void
     */
    public function deleted(UserProfileBalance $userProfileBalance): void
    {
        $this->clearCaches($userProfileBalance);
    }

    /**
     * Handle the UserProfileBalance "saved" event.
     *
     * @param UserProfileBalance $userProfileBalance
     * @return void
     */
    public function saved(UserProfileBalance $userProfileBalance): void
    {
        $this->clearCaches($userProfileBalance);
    }

    /**
     * Clear relevant caches.
     *
     * @param UserProfileBalance $userProfileBalance
     * @return void
     */
    private function clearCaches(UserProfileBalance $userProfileBalance): void
    {
        $this->forgetRowCountCache();
    }

    /**
     * Forget the user profile balance row count cache.
     * @return void
     */
    private function forgetRowCountCache(): void
    {
        Cache::forget('user-profile-balance-row-count');
    }
}
