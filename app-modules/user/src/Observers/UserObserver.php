<?php

declare(strict_types=1);

namespace Termehsoft\User\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Termehsoft\User\Models\User;

final class UserObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    /**
     * Handle the User "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $user->userProfiles()->each(function ($userProfile): void {
                $userProfile->userProfileDocuments()->delete();
                $userProfile->userProfilePhones()->delete();

                $userProfile->delete();
            });
        });

        $this->clearCaches($user);
    }

    /**
     * Handle the User "saved" event.
     *
     * @param User $user
     * @return void
     */
    public function saved(User $user): void
    {
        $this->clearCaches($user);
    }

    /**
     * Clear relevant caches.
     *
     * @param User $user
     * @return void
     */
    private function clearCaches(User $user): void
    {
        $this->forgetRowCountCache();
    }

    /**
     * Forget the user row count cache.
     * @return void
     */
    private function forgetRowCountCache(): void
    {
        Cache::forget('user-row-count');
    }
}
