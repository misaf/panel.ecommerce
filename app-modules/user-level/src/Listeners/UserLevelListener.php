<?php

declare(strict_types=1);

namespace Misaf\UserLevel\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Misaf\UserLevel\Models\UserLevel;
use Misaf\UserLevel\Models\UserLevelHistory;
use Misaf\UserRake\Events\UserRakeIncreasedEvent;

final class UserLevelListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handleRakeIncrease(UserRakeIncreasedEvent $event): void
    {
        $userId = $event->userId;
        $rake = $this->getUserRake($userId);

        $level = UserLevel::query()
            ->where('min_points', '<=', $rake)
            ->orderByDesc('min_points')
            ->first();

        if ( ! $level || $this->hasLevel($userId, $level->id)) {
            return;
        }

        UserLevelHistory::create([
            'user_id'       => $userId,
            'user_level_id' => $level->id,
        ]);
    }

    private function getUserRake(int $userId): float
    {
        $cacheKey = "user-rake-stats:user:{$userId}";
        $value = Cache::store('user_rake')->get($cacheKey, 0);

        if ( ! is_numeric($value)) {
            return 0.0;
        }

        return (float) $value;
    }

    private function hasLevel(int $userId, int $levelId): bool
    {
        return UserLevelHistory::query()
            ->where('user_id', $userId)
            ->where('user_level_id', $levelId)
            ->exists();
    }
}
