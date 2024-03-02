<?php

declare(strict_types=1);

namespace App\Observers\User;

use App\Models\User\UserProfilePhone;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class UserProfilePhoneObserver implements ShouldQueue
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

    public function created(UserProfilePhone $userProfilePhone): void {}

    public function deleted(UserProfilePhone $userProfilePhone): void {}

    public function forceDeleted(UserProfilePhone $userProfilePhone): void {}

    public function restored(UserProfilePhone $userProfilePhone): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(UserProfilePhone $userProfilePhone): void {}
}
