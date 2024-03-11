<?php

declare(strict_types=1);

namespace App\Observers\User;

use App\Models\User\UserProfileDocument;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class UserProfileDocumentObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function created(UserProfileDocument $userProfileDocument): void {}

    public function deleted(UserProfileDocument $userProfileDocument): void {}

    public function forceDeleted(UserProfileDocument $userProfileDocument): void {}

    public function restored(UserProfileDocument $userProfileDocument): void {}

    public function updated(UserProfileDocument $userProfileDocument): void {}
}
