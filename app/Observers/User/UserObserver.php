<?php

declare(strict_types=1);

namespace App\Observers\User;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class UserObserver implements ShouldQueue
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

    public function created(User $user): void {}

    public function deleted(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $user->userProfiles()->each(function ($userProfile): void {
                $userProfile->userProfileDocuments()->delete();
                $userProfile->userProfilePhones()->delete();

                $userProfile->delete();
            });
        });

        Cache::forget('user_row_count');
    }

    public function forceDeleted(User $user): void {}

    public function restored(User $user): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function saved(User $product): void
    {
        Cache::forget('user_row_count');
    }

    public function updated(User $user): void {}
}
