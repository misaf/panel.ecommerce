<?php

declare(strict_types=1);

namespace App\Models\User\Observers;

use App\Models\User\UserProfileBalance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class UserProfileBalanceObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    /**
     * Handle the UserProfileBalance "created" event.
     *
     * @param UserProfileBalance $userProfileBalance
     */
    public function created(UserProfileBalance $userProfileBalance): void {}

    /**
     * Handle the UserProfileBalance "deleted" event.
     *
     * @param UserProfileBalance $userProfileBalance
     */
    public function deleted(UserProfileBalance $userProfileBalance): void {}

    /**
     * Handle the UserProfileBalance "force deleted" event.
     *
     * @param UserProfileBalance $userProfileBalance
     */
    public function forceDeleted(UserProfileBalance $userProfileBalance): void {}

    /**
     * Handle the restored "created" event.
     *
     * @param UserProfileBalance $userProfileBalance
     */
    public function restored(UserProfileBalance $userProfileBalance): void {}

    /**
     * Handle the updated "created" event.
     *
     * @param UserProfileBalance $userProfileBalance
     */
    public function updated(UserProfileBalance $userProfileBalance): void {}
}
