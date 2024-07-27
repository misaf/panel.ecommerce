<?php

declare(strict_types=1);

namespace Termehsoft\User\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Termehsoft\User\Models\UserProfileDocument;

final class UserProfileDocumentObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    /**
     * Handle the UserProfileDocument "deleted" event.
     *
     * @param UserProfileDocument $userProfileDocument
     * @return void
     */
    public function deleted(UserProfileDocument $userProfileDocument): void
    {
        $this->clearCaches($userProfileDocument);
    }

    /**
     * Handle the UserProfileDocument "saved" event.
     *
     * @param UserProfileDocument $userProfileDocument
     * @return void
     */
    public function saved(UserProfileDocument $userProfileDocument): void
    {
        $this->clearCaches($userProfileDocument);
    }

    /**
     * Clear relevant caches.
     *
     * @param UserProfileDocument $userProfileDocument
     * @return void
     */
    private function clearCaches(UserProfileDocument $userProfileDocument): void
    {
        $this->forgetRowCountCache();
    }

    /**
     * Forget the user profile document row count cache.
     * @return void
     */
    private function forgetRowCountCache(): void
    {
        Cache::forget('user-profile-document-row-count');
    }
}
