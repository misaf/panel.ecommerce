<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class HealthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        \Spatie\Health\Facades\Health::checks([
            \Spatie\Health\Checks\Checks\OptimizedAppCheck::new(),
            \Spatie\Health\Checks\Checks\DebugModeCheck::new(),
            \Spatie\Health\Checks\Checks\EnvironmentCheck::new(),
            \Spatie\Health\Checks\Checks\RedisCheck::new(),
            \Spatie\CpuLoadHealthCheck\CpuLoadCheck::new()
                ->failWhenLoadIsHigherInTheLast5Minutes(5.0)
                ->failWhenLoadIsHigherInTheLast15Minutes(5),
            // \Spatie\Health\Checks\Checks\SecurityAdvisoriesCheck::new(),
            \Spatie\Health\Checks\Checks\UsedDiskSpaceCheck::new(),
            \Spatie\Health\Checks\Checks\DatabaseCheck::new(),
            \Spatie\Health\Checks\Checks\CacheCheck::new(),
        ]);
    }
}
