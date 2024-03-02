<?php

declare(strict_types=1);

namespace App\Observers\User;

use App\Models\User\UserProfileBalance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class UserProfileBalanceObserver implements ShouldQueue
{
    use InteractsWithQueue;

    //public $queue = 'listeners';

    public bool $afterCommit = true;

    public $maxExceptions = 5;

    public $tries = 5;

    public function backoff(): array
    {
        return [1, 5, 10, 30];
    }

    public function created(UserProfileBalance $userProfileBalance): void {}

    public function deleted(UserProfileBalance $userProfileBalance): void {}

    public function forceDeleted(UserProfileBalance $userProfileBalance): void {}

    public function restored(UserProfileBalance $userProfileBalance): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(UserProfileBalance $userProfileBalance): void {}
}
