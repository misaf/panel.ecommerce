<?php

declare(strict_types=1);

namespace Misaf\VendraAuthifyLog\Console\Commands;

use Exception;
use Illuminate\Support\Facades\Log;
use Misaf\AuthifyLog\Console\Commands\AuthifyLogChannelCommand as LaravelAuthifyLogChannelCommand;
use Misaf\AuthifyLog\Jobs\AuthifyLogJob;
use Misaf\VendraSupport\Contracts\TenantResolver;
use Misaf\VendraSupport\Support\TenantAwareness;

class AuthifyLogChannelCommand extends LaravelAuthifyLogChannelCommand
{
    protected $signature = 'vendra-authify-log:channel';

    private function processBatch(array $entries): void
    {
        $decodedEntries = array_map(fn($item) => json_decode($item, true), $entries);
        $groupedEntries = collect($decodedEntries)->groupBy('tenant_id')->toArray();

        foreach ($groupedEntries as $tenantId => $groupedLogs) {
            if ( ! is_array($groupedLogs)) {
                continue;
            }

            $this->dispatchJobForTenant((int) $tenantId, $groupedLogs);
        }
    }

    /**
     * @param  array<int, array<string, int|string>>  $groupedLogs
     */
    private function dispatchJobForTenant(int $tenantId, array $groupedLogs): void
    {
        try {
            if (TenantAwareness::enabled() && ! app(TenantResolver::class)->makeCurrent($tenantId)) {
                Log::error('Failed to dispatch job for tenant.', ["Tenant [{$tenantId}] was not found."]);

                return;
            }

            AuthifyLogJob::dispatch($groupedLogs);
        } catch (Exception $e) {
            Log::error('Failed to dispatch job for tenant.', [$e->getMessage()]);
        }
    }
}
