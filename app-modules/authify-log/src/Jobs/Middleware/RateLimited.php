<?php

declare(strict_types=1);

namespace Misaf\AuthifyLog\Jobs\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Misaf\AuthifyLog\Jobs\AuthifyLogJob;

final class RateLimited
{
    public function handle(AuthifyLogJob $job, Closure $next): void
    {
        Redis::throttle('authify-log-throttle')
            ->block(0)
            ->allow(1)
            ->every(5)
            ->then(
                function () use ($job, $next): void {
                    $next($job);
                },
                function () use ($job): void {
                    Log::warning(
                        'AuthifyLogJob delayed due to rate limiting, will retry in 5 seconds.',
                        ['job' => get_class($job)],
                    );
                    $job->release(5);
                },
            );
    }
}
