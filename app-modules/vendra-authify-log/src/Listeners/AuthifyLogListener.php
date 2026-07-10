<?php

declare(strict_types=1);

namespace Misaf\VendraAuthifyLog\Listeners;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use Misaf\AuthifyLog\Enums\AuthifyLogActionEnum;
use Misaf\AuthifyLog\Listeners\AuthifyLogListener as LaravelAuthifyLogListener;
use Misaf\VendraSupport\Support\TenantAwareness;

class AuthifyLogListener extends LaravelAuthifyLogListener
{
    private function store(AuthifyLogActionEnum $action, object $event): void
    {
        $userId = isset($event->user) ? $event->user->id : null;

        $timestamp = Carbon::now()->toDateTimeString();
        $logEntry = [
            'tenant_id'  => TenantAwareness::currentId(),
            'user_id'    => $userId,
            'action'     => $action->value,
            'ip_address' => request()->ip(),
            'ip_country' => request()->header('CF-IPCountry') ?? 'XX',
            'user_agent' => request()->userAgent(),
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];

        $authifyTransaction = Redis::connection('authify_log')->rpush('entries', json_encode($logEntry));
        Redis::connection('authify_log_channel')->publish('authify-log-channel', $authifyTransaction);
    }
}
