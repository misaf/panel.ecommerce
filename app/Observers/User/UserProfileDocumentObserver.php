<?php

declare(strict_types=1);

namespace App\Observers\User;

use App\Models\User\UserProfileDocument;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class UserProfileDocumentObserver implements ShouldQueue
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

    public function created(UserProfileDocument $userProfileDocument): void {}

    public function deleted(UserProfileDocument $userProfileDocument): void {}

    public function forceDeleted(UserProfileDocument $userProfileDocument): void {}

    public function restored(UserProfileDocument $userProfileDocument): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(UserProfileDocument $userProfileDocument): void {}
}
