<?php

declare(strict_types=1);

namespace Misaf\AuthifyLog\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Misaf\AuthifyLog\Jobs\Middleware\RateLimited;
use Misaf\AuthifyLog\Models\AuthifyLog;
use Throwable;

final class AuthifyLogJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param  array<int, array<string, int|string>>  $records
     */
    public function __construct(public array $records) {}

    public function handle(): void
    {
        try {
            AuthifyLog::insert($this->records);
        } catch (Throwable $e) {
            Log::critical('Unexpected error during AuthifyLogJob execution', ['error' => $e->getMessage()]);
        }
    }

    // /**
    //  * @return array<int, object>
    //  */
    // public function middleware(): array
    // {
    //     return [new RateLimited()];
    // }

    public function failed(?Throwable $exception): void
    {
        $errorMessage = $exception ? $exception->getMessage() : 'Unknown error occurred.';
        Log::error('AuthifyLogJob failed after maximum attempts.', [$errorMessage]);
    }
}
