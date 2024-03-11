<?php

declare(strict_types=1);

namespace App\Observers\User;

use App\Models\User\UserProfilePhone;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class UserProfilePhoneObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function created(UserProfilePhone $userProfilePhone): void {}

    public function deleted(UserProfilePhone $userProfilePhone): void {}

    public function forceDeleted(UserProfilePhone $userProfilePhone): void {}

    public function restored(UserProfilePhone $userProfilePhone): void {}

    public function updated(UserProfilePhone $userProfilePhone): void {}
}
