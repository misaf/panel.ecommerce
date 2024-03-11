<?php

declare(strict_types=1);

namespace App\Observers\User;

use App\Models\User\UserProfileBalance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class UserProfileBalanceObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function created(UserProfileBalance $userProfileBalance): void {}

    public function deleted(UserProfileBalance $userProfileBalance): void {}

    public function forceDeleted(UserProfileBalance $userProfileBalance): void {}

    public function restored(UserProfileBalance $userProfileBalance): void {}

    public function updated(UserProfileBalance $userProfileBalance): void {}
}
